import { Controller } from '@hotwired/stimulus';
import { Drawer, type DrawerOptions } from 'flowbite';

import type { ValueDefinitionMap } from '@hotwired/stimulus/dist/types/core/value_properties';

/* stimulusFetch: 'lazy' */
export default class DrawerController extends Controller<HTMLElement> {
  static values: ValueDefinitionMap = {
    options: { type: Object, default: {} },
  };

  static targets = ['drawer'];

  declare optionsValue: DrawerOptions;

  declare readonly hasOptionsValue: boolean;

  declare readonly drawerTarget: HTMLElement;

  declare readonly hasDrawerTarget: boolean;

  private drawer: Drawer | null = null;

  connect() {
    this.drawer = new Drawer(this.hasDrawerTarget ? this.drawerTarget : this.element, this.optionsValue);
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
}
