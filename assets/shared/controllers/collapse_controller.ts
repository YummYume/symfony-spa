import { Controller } from '@hotwired/stimulus';
import Collapse from 'flowbite/lib/esm/components/collapse';

import { COLLAPSE_EVENTS } from '$assets/types/constants/collapse';

import type { ValueDefinitionMap } from '@hotwired/stimulus/dist/types/core/value_properties';
import type { CollapseOptions, CollapseInterface } from 'flowbite/lib/esm';

/* stimulusFetch: 'lazy' */
export default class CollapseController extends Controller<HTMLElement> {
  static values: ValueDefinitionMap = {
    options: { type: Object, default: {} },
    eventPrefix: String,
  };

  static targets = ['parent', 'trigger'];

  declare optionsValue: CollapseOptions;

  declare readonly hasOptionsValue: boolean;

  declare eventPrefixValue: string;

  declare readonly hasEventPrefixValue: boolean;

  declare readonly parentTarget: HTMLElement;

  declare readonly hasParentTarget: boolean;

  declare readonly triggerTarget: HTMLElement;

  declare readonly hasTriggerTarget: boolean;

  private target = this.element;

  private eventPrefix: string | undefined = undefined;

  private collapseInstance: CollapseInterface | null = null;

  connect() {
    this.target = this.hasParentTarget ? this.parentTarget : this.element;
    this.eventPrefix = this.hasEventPrefixValue ? this.eventPrefixValue : undefined;
    this.collapseInstance = new Collapse(
      this.target,
      this.hasTriggerTarget ? this.triggerTarget : undefined,
      { ...this.defaultOptions, ...this.optionsValue },
    );

    document.addEventListener('turbo:before-cache', this.beforeCache);
  }

  disconnect() {
    document.removeEventListener('turbo:before-cache', this.beforeCache);
  }

  collapse() {
    if (!this.collapseInstance) {
      return;
    }

    this.collapseInstance.collapse();
  }

  expand() {
    if (!this.collapseInstance) {
      return;
    }

    this.collapseInstance.expand();
  }

  toggle() {
    if (!this.collapseInstance) {
      return;
    }

    this.collapseInstance.toggle();
  }

  isInitialized() {
    return !!this.collapseInstance;
  }

  private beforeCache = () => {
    if (!this.collapseInstance) {
      return;
    }

    this.collapseInstance.collapse();
  };

  get defaultOptions(): CollapseOptions {
    return {
      onCollapse: () => {
        this.dispatch(COLLAPSE_EVENTS.COLLAPSE, {
          target: this.target,
          detail: { collapse: this.collapseInstance },
          prefix: this.eventPrefix,
        });
      },
      onExpand: () => {
        this.dispatch(COLLAPSE_EVENTS.EXPAND, {
          target: this.target,
          detail: { collapse: this.collapseInstance },
          prefix: this.eventPrefix,
        });
      },
      onToggle: () => {
        this.dispatch(COLLAPSE_EVENTS.TOGGLE, {
          target: this.target,
          detail: { collapse: this.collapseInstance },
          prefix: this.eventPrefix,
        });
      },
    };
  }
}
