import { Controller } from '@hotwired/stimulus';
import { Dropdown, type DropdownOptions } from 'flowbite';

import type { ValueDefinitionMap } from '@hotwired/stimulus/dist/types/core/value_properties';

/* stimulusFetch: 'lazy' */
export default class DropdownController extends Controller<HTMLElement> {
  static values: ValueDefinitionMap = {
    options: { type: Object, default: {} },
  };

  static targets = ['dropdown', 'trigger'];

  declare optionsValue: DropdownOptions;

  declare readonly hasOptionsValue: boolean;

  declare readonly dropdownTarget: HTMLElement;

  declare readonly hasDropdownTarget: boolean;

  declare readonly triggerTarget: HTMLElement;

  declare readonly hasTriggerTarget: boolean;

  private dropdown: Dropdown | null = null;

  connect() {
    this.dropdown = new Dropdown(
      this.hasDropdownTarget ? this.dropdownTarget : this.element,
      this.hasTriggerTarget ? this.triggerTarget : undefined,
      this.optionsValue,
    );

    document.addEventListener('turbo:before-cache', this.beforeCache);
  }

  disconnect(): void {
    document.removeEventListener('turbo:before-cache', this.beforeCache);
  }

  show() {
    if (!this.dropdown) {
      return;
    }

    this.dropdown.show();
  }

  hide() {
    if (!this.dropdown) {
      return;
    }

    this.dropdown.hide();
  }

  toggle() {
    if (!this.dropdown) {
      return;
    }

    this.dropdown.toggle();
  }

  private beforeCache = () => {
    if (!this.dropdown) {
      return;
    }

    this.dropdown.hide();
  };
}
