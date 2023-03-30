import { ActionEvent, Controller } from '@hotwired/stimulus';
import Accordion from 'flowbite/lib/esm/components/accordion';

import { ACCORDION_EVENTS } from '$types/constants/accordion';

import type { ValueDefinitionMap } from '@hotwired/stimulus/dist/types/core/value_properties';
import type { AccordionOptions, AccordionInterface } from 'flowbite/lib/esm';

/* stimulusFetch: 'lazy' */
export default class AccordionController extends Controller<HTMLElement> {
  static values: ValueDefinitionMap = {
    options: { type: Object, default: {} },
    eventPrefix: String,
  };

  static targets = ['item', 'target', 'icon'];

  declare optionsValue: AccordionOptions;

  declare readonly hasOptionsValue: boolean;

  declare eventPrefixValue: string;

  declare readonly hasEventPrefixValue: boolean;

  declare readonly itemTarget: HTMLElement;

  declare readonly itemTargets: HTMLElement[];

  declare readonly hasItemTarget: boolean;

  declare readonly targetTarget: HTMLElement;

  declare readonly targetTargets: HTMLElement[];

  declare readonly hasTargetTarget: boolean;

  declare readonly iconTarget: HTMLElement;

  declare readonly iconTargets: HTMLElement[];

  declare readonly hasIconTarget: boolean;

  private eventPrefix: string | undefined = undefined;

  private accordion: AccordionInterface | null = null;

  connect() {
    this.eventPrefix = this.hasEventPrefixValue ? this.eventPrefixValue : undefined;
    this.accordion = new Accordion(
      this.itemTargets.map((item, id) => {
        const targetEl = this.targetTargets.at(id);

        if (!targetEl) {
          throw new Error('An accordion item must be associated with a target.');
        }

        return ({
          id: item.id,
          triggerEl: item,
          targetEl,
          active: id === 0,
          iconEl: this.iconTargets.at(id),
        });
      }),
      { ...this.defaultOptions, ...this.optionsValue },
    );

    document.addEventListener('turbo:before-cache', this.beforeCache);
  }

  disconnect() {
    document.removeEventListener('turbo:before-cache', this.beforeCache);
  }

  show({ params }: ActionEvent) {
    if (!this.accordion || typeof params.open !== 'string') {
      return;
    }

    this.accordion.open(params.open);
  }

  close({ params }: ActionEvent) {
    if (!this.accordion || typeof params.open !== 'string') {
      return;
    }

    this.accordion.close(params.open);
  }

  toggle({ params }: ActionEvent) {
    if (!this.accordion || typeof params.open !== 'string') {
      return;
    }

    this.accordion.toggle(params.open);
  }

  isHidden(id: string) {
    if (!this.accordion) {
      return true;
    }

    return this.accordion.getItem(id);
  }

  isInitialized() {
    return !!this.accordion;
  }

  private beforeCache = () => {
    if (!this.accordion) {
      return;
    }

    this.itemTargets.forEach((item) => {
      this.accordion?.close(item.id);
    });
  };

  get defaultOptions(): AccordionOptions {
    return {
      onOpen: (accordion, accordionItem) => {
        this.dispatch(ACCORDION_EVENTS.OPEN, {
          target: this.element,
          detail: { accordion: this.accordion, item: accordionItem },
          prefix: this.eventPrefix,
        });
      },
      onClose: (accordion, accordionItem) => {
        this.dispatch(ACCORDION_EVENTS.CLOSE, {
          target: this.element,
          detail: { accordion: this.accordion, item: accordionItem },
          prefix: this.eventPrefix,
        });
      },
      onToggle: (accordion, accordionItem) => {
        this.dispatch(ACCORDION_EVENTS.TOGGLE, {
          target: this.element,
          detail: { accordion: this.accordion, item: accordionItem },
          prefix: this.eventPrefix,
        });
      },
    };
  }
}
