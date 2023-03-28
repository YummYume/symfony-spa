import { Controller } from '@hotwired/stimulus';
import { Tooltip, type TooltipOptions } from 'flowbite';

import type { ValueDefinitionMap } from '@hotwired/stimulus/dist/types/core/value_properties';

/* stimulusFetch: 'lazy' */
export default class PopoverController extends Controller<HTMLElement> {
  static values: ValueDefinitionMap = {
    options: { type: Object, default: {} },
  };

  static targets = ['tooltip', 'trigger'];

  declare optionsValue: TooltipOptions;

  declare readonly hasOptionsValue: boolean;

  declare readonly tooltipTarget: HTMLElement;

  declare readonly hasTooltipTarget: boolean;

  declare readonly triggerTarget: HTMLElement;

  declare readonly hasTriggerTarget: boolean;

  private tooltip: Tooltip | null = null;

  connect() {
    this.tooltip = new Tooltip(
      this.hasTooltipTarget ? this.tooltipTarget : this.element,
      this.hasTriggerTarget ? this.triggerTarget : undefined,
      this.optionsValue,
    );

    document.addEventListener('turbo:before-cache', this.beforeCache);
  }

  disconnect(): void {
    document.removeEventListener('turbo:before-cache', this.beforeCache);
  }

  show() {
    if (!this.tooltip) {
      return;
    }

    this.tooltip.show();
  }

  hide() {
    if (!this.tooltip) {
      return;
    }

    this.tooltip.hide();
  }

  toggle() {
    if (!this.tooltip) {
      return;
    }

    this.tooltip.toggle();
  }

  private beforeCache = () => {
    if (!this.tooltip) {
      return;
    }

    this.tooltip.hide();
  };
}
