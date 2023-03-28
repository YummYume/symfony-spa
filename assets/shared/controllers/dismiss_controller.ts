import { Controller } from '@hotwired/stimulus';
import { Dismiss, type DismissOptions, type DismissInterface } from 'flowbite';

import { DISMISS_EVENTS } from '$assets/types/constants/dismiss';

import type { ValueDefinitionMap } from '@hotwired/stimulus/dist/types/core/value_properties';

/* stimulusFetch: 'lazy' */
export default class DismissController extends Controller<HTMLElement> {
  static values: ValueDefinitionMap = {
    options: { type: Object, default: {} },
    eventPrefix: String,
  };

  static targets = ['dismiss', 'trigger'];

  declare optionsValue: DismissOptions;

  declare readonly hasOptionsValue: boolean;

  declare eventPrefixValue: string;

  declare readonly hasEventPrefixValue: boolean;

  declare readonly dismissTarget: HTMLElement;

  declare readonly hasDismissTarget: boolean;

  declare readonly triggerTarget: HTMLElement;

  declare readonly hasTriggerTarget: boolean;

  private target = this.element;

  private eventPrefix: string | undefined = undefined;

  private dismiss: DismissInterface | null = null;

  connect() {
    this.target = this.hasDismissTarget ? this.dismissTarget : this.element;
    this.eventPrefix = this.hasEventPrefixValue ? this.eventPrefixValue : undefined;
    this.dismiss = new Dismiss(
      this.target,
      this.hasTriggerTarget ? this.triggerTarget : undefined,
      { ...this.defaultValues, ...this.optionsValue },
    );

    document.addEventListener('turbo:before-cache', this.beforeCache);
  }

  disconnect() {
    document.removeEventListener('turbo:before-cache', this.beforeCache);
  }

  hide() {
    if (!this.dismiss) {
      return;
    }

    this.dismiss.hide();
  }

  isInitialized() {
    return !!this.dismiss;
  }

  private beforeCache = () => {
    if (!this.dismiss) {
      return;
    }

    this.dismiss.hide();
  };

  get defaultValues(): DismissOptions {
    return {
      onHide: () => {
        this.dispatch(DISMISS_EVENTS.HIDE, {
          target: this.target,
          detail: { dismiss: this.dismiss },
          prefix: this.eventPrefix,
        });
      },
    };
  }
}
