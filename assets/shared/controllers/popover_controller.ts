import { Controller } from '@hotwired/stimulus';
import Popover from 'flowbite/lib/esm/components/popover';

import { POPOVER_EVENTS } from '$assets/types/constants/popover';

import type { ValueDefinitionMap } from '@hotwired/stimulus/dist/types/core/value_properties';
import type { PopoverOptions, PopoverInterface } from 'flowbite/lib/esm';

/* stimulusFetch: 'lazy' */
export default class PopoverController extends Controller<HTMLElement> {
  static values: ValueDefinitionMap = {
    options: { type: Object, default: {} },
    eventPrefix: String,
  };

  static targets = ['popover', 'trigger'];

  declare optionsValue: PopoverOptions;

  declare readonly hasOptionsValue: boolean;

  declare eventPrefixValue: string;

  declare readonly hasEventPrefixValue: boolean;

  declare readonly popoverTarget: HTMLElement;

  declare readonly hasPopoverTarget: boolean;

  declare readonly triggerTarget: HTMLElement;

  declare readonly hasTriggerTarget: boolean;

  private target = this.element;

  private eventPrefix: string | undefined = undefined;

  private popover: PopoverInterface | null = null;

  connect() {
    this.target = this.hasPopoverTarget ? this.popoverTarget : this.element;
    this.eventPrefix = this.hasEventPrefixValue ? this.eventPrefixValue : undefined;
    this.popover = new Popover(
      this.target,
      this.hasTriggerTarget ? this.triggerTarget : undefined,
      { ...this.defaultOptions, ...this.optionsValue },
    );

    document.addEventListener('turbo:before-cache', this.beforeCache);
  }

  disconnect() {
    document.removeEventListener('turbo:before-cache', this.beforeCache);
  }

  show() {
    if (!this.popover) {
      return;
    }

    this.popover.show();
  }

  hide() {
    if (!this.popover) {
      return;
    }

    this.popover.hide();
  }

  toggle() {
    if (!this.popover) {
      return;
    }

    this.popover.toggle();
  }

  isVisible() {
    if (!this.popover) {
      return false;
    }

    return this.popover.isVisible();
  }

  isInitialized() {
    return !!this.popover;
  }

  private beforeCache = () => {
    if (!this.popover) {
      return;
    }

    this.popover.hide();
  };

  get defaultOptions(): PopoverOptions {
    return {
      onHide: () => {
        this.dispatch(POPOVER_EVENTS.HIDE, {
          target: this.target,
          detail: { popover: this.popover },
          prefix: this.eventPrefix,
        });
      },
      onShow: () => {
        this.dispatch(POPOVER_EVENTS.SHOW, {
          target: this.target,
          detail: { popover: this.popover },
          prefix: this.eventPrefix,
        });
      },
      onToggle: () => {
        this.dispatch(POPOVER_EVENTS.TOGGLE, {
          target: this.target,
          detail: { popover: this.popover },
          prefix: this.eventPrefix,
        });
      },
    };
  }
}
