import { Controller } from '@hotwired/stimulus';
import { Dial, type DialOptions, type DialInterface } from 'flowbite';

import { DIAL_EVENTS } from '$assets/types/constants/dial';

import type { ValueDefinitionMap } from '@hotwired/stimulus/dist/types/core/value_properties';

/* stimulusFetch: 'lazy' */
export default class DialController extends Controller<HTMLElement> {
  static values: ValueDefinitionMap = {
    options: { type: Object, default: {} },
    eventPrefix: String,
  };

  static targets = ['parent', 'trigger', 'target'];

  declare optionsValue: DialOptions;

  declare readonly hasOptionsValue: boolean;

  declare eventPrefixValue: string;

  declare readonly hasEventPrefixValue: boolean;

  declare readonly parentTarget: HTMLElement;

  declare readonly hasParentTarget: boolean;

  declare readonly triggerTarget: HTMLElement;

  declare readonly hasTriggerTarget: boolean;

  declare readonly targetTarget: HTMLElement;

  declare readonly hasTargetTarget: boolean;

  private target = this.element;

  private eventPrefix: string | undefined = undefined;

  private dial: DialInterface | null = null;

  connect() {
    this.target = this.hasParentTarget ? this.parentTarget : this.element;
    this.eventPrefix = this.hasEventPrefixValue ? this.eventPrefixValue : undefined;
    this.dial = new Dial(
      this.target,
      this.hasTriggerTarget ? this.triggerTarget : undefined,
      this.hasTargetTarget ? this.targetTarget : undefined,
      { ...this.defaultOptions, ...this.optionsValue },
    );

    document.addEventListener('turbo:before-cache', this.beforeCache);
  }

  disconnect() {
    document.removeEventListener('turbo:before-cache', this.beforeCache);
  }

  show() {
    if (!this.dial) {
      return;
    }

    this.dial.show();
  }

  hide() {
    if (!this.dial) {
      return;
    }

    this.dial.hide();
  }

  toggle() {
    if (!this.dial) {
      return;
    }

    this.dial.toggle();
  }

  isVisible() {
    if (!this.dial) {
      return false;
    }

    return this.dial.isVisible();
  }

  isHidden() {
    if (!this.dial) {
      return true;
    }

    return this.dial.isHidden();
  }

  isInitialized() {
    return !!this.dial;
  }

  private beforeCache = () => {
    if (!this.dial) {
      return;
    }

    this.dial.hide();
  };

  get defaultOptions(): DialOptions {
    return {
      onHide: () => {
        this.dispatch(DIAL_EVENTS.HIDE, {
          target: this.target,
          detail: { dial: this.dial },
          prefix: this.eventPrefix,
        });
      },
      onShow: () => {
        this.dispatch(DIAL_EVENTS.SHOW, {
          target: this.target,
          detail: { dial: this.dial },
          prefix: this.eventPrefix,
        });
      },
      onToggle: () => {
        this.dispatch(DIAL_EVENTS.TOGGLE, {
          target: this.target,
          detail: { dial: this.dial },
          prefix: this.eventPrefix,
        });
      },
    };
  }
}
