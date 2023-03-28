import { Controller } from '@hotwired/stimulus';
import { Popover, type PopoverOptions } from 'flowbite';

import type { ValueDefinitionMap } from '@hotwired/stimulus/dist/types/core/value_properties';

/* stimulusFetch: 'lazy' */
export default class PopoverController extends Controller<HTMLElement> {
  static values: ValueDefinitionMap = {
    options: { type: Object, default: {} },
  };

  static targets = ['popover', 'trigger'];

  declare optionsValue: PopoverOptions;

  declare readonly hasOptionsValue: boolean;

  declare readonly popoverTarget: HTMLElement;

  declare readonly hasPopoverTarget: boolean;

  declare readonly triggerTarget: HTMLElement;

  declare readonly hasTriggerTarget: boolean;

  private popover: Popover | null = null;

  connect() {
    this.popover = new Popover(
      this.hasPopoverTarget ? this.popoverTarget : this.element,
      this.hasTriggerTarget ? this.triggerTarget : undefined,
      this.optionsValue,
    );

    document.addEventListener('turbo:before-cache', this.beforeCache);
  }

  disconnect(): void {
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

  private beforeCache = () => {
    if (!this.popover) {
      return;
    }

    this.popover.hide();
  };
}
