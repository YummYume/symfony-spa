import { Controller } from '@hotwired/stimulus';
import { Dropdown, type DropdownOptions, type DropdownInterface } from 'flowbite';

import { DROPDOWN_EVENTS } from '$assets/types/constants/dropdown';

import type { ValueDefinitionMap } from '@hotwired/stimulus/dist/types/core/value_properties';

/* stimulusFetch: 'lazy' */
export default class DropdownController extends Controller<HTMLElement> {
  static values: ValueDefinitionMap = {
    options: { type: Object, default: {} },
    eventPrefix: { type: String },
  };

  static targets = ['dropdown', 'trigger'];

  declare optionsValue: DropdownOptions;

  declare readonly hasOptionsValue: boolean;

  declare eventPrefixValue: string;

  declare readonly hasEventPrefixValue: boolean;

  declare readonly dropdownTarget: HTMLElement;

  declare readonly hasDropdownTarget: boolean;

  declare readonly triggerTarget: HTMLElement;

  declare readonly hasTriggerTarget: boolean;

  private target = this.element;

  private eventPrefix: string | undefined = undefined;

  private dropdown: DropdownInterface | null = null;

  connect() {
    this.target = this.hasDropdownTarget ? this.dropdownTarget : this.element;
    this.eventPrefix = this.hasEventPrefixValue ? this.eventPrefixValue : undefined;
    this.dropdown = new Dropdown(
      this.target,
      this.hasTriggerTarget ? this.triggerTarget : undefined,
      { ...this.defaultValues, ...this.optionsValue },
    );

    document.addEventListener('turbo:before-cache', this.beforeCache);
  }

  disconnect() {
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

  isVisible() {
    if (!this.dropdown) {
      return false;
    }

    return this.dropdown.isVisible();
  }

  isInitialized() {
    return !!this.dropdown;
  }

  private beforeCache = () => {
    if (!this.dropdown) {
      return;
    }

    this.dropdown.hide();
  };

  get defaultValues(): DropdownOptions {
    return {
      onHide: () => {
        this.dispatch(DROPDOWN_EVENTS.HIDE, {
          target: this.target,
          detail: { dropdown: this.dropdown },
          prefix: this.eventPrefix,
        });
      },
      onShow: () => {
        this.dispatch(DROPDOWN_EVENTS.SHOW, {
          target: this.target,
          detail: { dropdown: this.dropdown },
          prefix: this.eventPrefix,
        });
      },
      onToggle: () => {
        this.dispatch(DROPDOWN_EVENTS.TOGGLE, {
          target: this.target,
          detail: { dropdown: this.dropdown },
          prefix: this.eventPrefix,
        });
      },
    };
  }
}
