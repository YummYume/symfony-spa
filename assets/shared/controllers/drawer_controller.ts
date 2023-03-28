import { Controller } from '@hotwired/stimulus';
import { Drawer, type DrawerOptions, type DrawerInterface } from 'flowbite';

import { DRAWER_EVENTS } from '$assets/types/constants/drawer';

import type { ValueDefinitionMap } from '@hotwired/stimulus/dist/types/core/value_properties';

/* stimulusFetch: 'lazy' */
export default class DrawerController extends Controller<HTMLElement> {
  static values: ValueDefinitionMap = {
    options: { type: Object, default: {} },
    eventPrefix: String,
  };

  static targets = ['drawer'];

  declare optionsValue: DrawerOptions;

  declare readonly hasOptionsValue: boolean;

  declare eventPrefixValue: string;

  declare readonly hasEventPrefixValue: boolean;

  declare readonly drawerTarget: HTMLElement;

  declare readonly hasDrawerTarget: boolean;

  private target = this.element;

  private eventPrefix: string | undefined = undefined;

  private drawer: DrawerInterface | null = null;

  connect() {
    this.target = this.hasDrawerTarget ? this.drawerTarget : this.element;
    this.eventPrefix = this.hasEventPrefixValue ? this.eventPrefixValue : undefined;
    this.drawer = new Drawer(this.target, { ...this.defaultValues, ...this.optionsValue });

    document.addEventListener('turbo:before-cache', this.beforeCache);
  }

  disconnect() {
    document.removeEventListener('turbo:before-cache', this.beforeCache);
  }

  show() {
    if (!this.drawer) {
      return;
    }

    this.drawer.show();
  }

  hide() {
    if (!this.drawer) {
      return;
    }

    this.drawer.hide();
  }

  toggle() {
    if (!this.drawer) {
      return;
    }

    this.drawer.toggle();
  }

  isVisible() {
    if (!this.drawer) {
      return false;
    }

    return this.drawer.isVisible();
  }

  isHidden() {
    if (!this.drawer) {
      return true;
    }

    return this.drawer.isHidden();
  }

  isInitialized() {
    return !!this.drawer;
  }

  private beforeCache = () => {
    if (!this.drawer) {
      return;
    }

    this.drawer.hide();
  };

  get defaultValues(): DrawerOptions {
    return {
      onHide: () => {
        this.dispatch(DRAWER_EVENTS.HIDE, {
          target: this.target,
          detail: { drawer: this.drawer },
          prefix: this.eventPrefix,
        });
      },
      onShow: () => {
        this.dispatch(DRAWER_EVENTS.SHOW, {
          target: this.target,
          detail: { drawer: this.drawer },
          prefix: this.eventPrefix,
        });
      },
      onToggle: () => {
        this.dispatch(DRAWER_EVENTS.TOGGLE, {
          target: this.target,
          detail: { drawer: this.drawer },
          prefix: this.eventPrefix,
        });
      },
    };
  }
}
