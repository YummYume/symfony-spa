import { Controller } from '@hotwired/stimulus';
import Tooltip from 'flowbite/lib/esm/components/tooltip';

import { TOOLTIP_EVENTS } from '$assets/types/constants/tooltip';

import type { ValueDefinitionMap } from '@hotwired/stimulus/dist/types/core/value_properties';
import type { TooltipOptions, TooltipInterface } from 'flowbite/lib/esm';

/* stimulusFetch: 'lazy' */
export default class TooltipController extends Controller<HTMLElement> {
  static values: ValueDefinitionMap = {
    options: { type: Object, default: {} },
    eventPrefix: String,
  };

  static targets = ['tooltip', 'trigger'];

  declare optionsValue: TooltipOptions;

  declare readonly hasOptionsValue: boolean;

  declare eventPrefixValue: string;

  declare readonly hasEventPrefixValue: boolean;

  declare readonly tooltipTarget: HTMLElement;

  declare readonly hasTooltipTarget: boolean;

  declare readonly triggerTarget: HTMLElement;

  declare readonly hasTriggerTarget: boolean;

  private target = this.element;

  private eventPrefix: string | undefined = undefined;

  private tooltip: TooltipInterface | null = null;

  connect() {
    this.target = this.hasTooltipTarget ? this.tooltipTarget : this.element;
    this.eventPrefix = this.hasEventPrefixValue ? this.eventPrefixValue : undefined;
    this.tooltip = new Tooltip(
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

  isVisible() {
    if (!this.tooltip) {
      return false;
    }

    return this.tooltip.isVisible();
  }

  isInitialized() {
    return !!this.tooltip;
  }

  private beforeCache = () => {
    if (!this.tooltip) {
      return;
    }

    this.tooltip.hide();
  };

  get defaultOptions(): TooltipOptions {
    return {
      onHide: () => {
        this.dispatch(TOOLTIP_EVENTS.HIDE, {
          target: this.target,
          detail: { tooltip: this.tooltip },
          prefix: this.eventPrefix,
        });
      },
      onShow: () => {
        this.dispatch(TOOLTIP_EVENTS.SHOW, {
          target: this.target,
          detail: { tooltip: this.tooltip },
          prefix: this.eventPrefix,
        });
      },
      onToggle: () => {
        this.dispatch(TOOLTIP_EVENTS.TOGGLE, {
          target: this.target,
          detail: { tooltip: this.tooltip },
          prefix: this.eventPrefix,
        });
      },
    };
  }
}
