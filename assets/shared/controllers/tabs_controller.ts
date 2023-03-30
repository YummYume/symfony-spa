import { ActionEvent, Controller } from '@hotwired/stimulus';
import Tabs from 'flowbite/lib/esm/components/tabs';

import { TABS_EVENTS } from '$assets/types/constants/tabs';

import type { ValueDefinitionMap } from '@hotwired/stimulus/dist/types/core/value_properties';
import type { TabsOptions, TabsInterface } from 'flowbite/lib/esm';

/* stimulusFetch: 'lazy' */
export default class TabsController extends Controller<HTMLElement> {
  static values: ValueDefinitionMap = {
    options: { type: Object, default: {} },
    eventPrefix: String,
  };

  static targets = ['item', 'target'];

  declare optionsValue: TabsOptions;

  declare readonly hasOptionsValue: boolean;

  declare eventPrefixValue: string;

  declare readonly hasEventPrefixValue: boolean;

  declare readonly itemTarget: HTMLElement;

  declare readonly itemTargets: HTMLElement[];

  declare readonly hasItemTarget: boolean;

  declare readonly targetTarget: HTMLElement;

  declare readonly targetTargets: HTMLElement[];

  declare readonly hasTargetTarget: boolean;

  private eventPrefix: string | undefined = undefined;

  private tabs: TabsInterface | null = null;

  connect() {
    this.eventPrefix = this.hasEventPrefixValue ? this.eventPrefixValue : undefined;
    this.tabs = new Tabs(
      this.itemTargets.map((item, id) => {
        const targetEl = this.targetTargets.at(id);

        if (!targetEl) {
          throw new Error('A tabs item must be associated with a target.');
        }

        return ({
          id: item.id,
          triggerEl: item,
          targetEl,
        });
      }),
      { ...this.defaultOptions, ...this.optionsValue },
    );
  }

  show({ params }: ActionEvent) {
    if (!this.tabs || typeof params.open !== 'string') {
      return;
    }

    this.tabs.show(params.open);
  }

  getActiveTab() {
    if (!this.tabs) {
      return;
    }

    this.tabs.getActiveTab();
  }

  getTab(id: string) {
    if (!this.tabs) {
      return null;
    }

    return this.tabs.getTab(id);
  }

  isInitialized() {
    return !!this.tabs;
  }

  get defaultOptions(): TabsOptions {
    return {
      onShow: (tabs, tabItem) => {
        this.dispatch(TABS_EVENTS.SHOW, {
          target: this.element,
          detail: { tabs: this.tabs, item: tabItem },
          prefix: this.eventPrefix,
        });
      },
    };
  }
}
