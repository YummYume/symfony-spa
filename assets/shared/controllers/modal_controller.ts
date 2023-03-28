import { Controller } from '@hotwired/stimulus';
import { Modal, type ModalOptions } from 'flowbite';

import type { ValueDefinitionMap } from '@hotwired/stimulus/dist/types/core/value_properties';

/* stimulusFetch: 'lazy' */
export default class ModalController extends Controller<HTMLElement> {
  static values: ValueDefinitionMap = {
    options: { type: Object, default: {} },
  };

  static targets = ['modal'];

  declare optionsValue: ModalOptions;

  declare readonly hasOptionsValue: boolean;

  declare readonly modalTarget: HTMLElement;

  declare readonly hasModalTarget: boolean;

  private modal: Modal | null = null;

  connect() {
    this.modal = new Modal(this.hasModalTarget ? this.modalTarget : this.element, this.optionsValue);
  }

  show() {
    if (!this.modal) {
      return;
    }

    this.modal.show();
  }

  hide() {
    if (!this.modal) {
      return;
    }

    this.modal.hide();
  }

  toggle() {
    if (!this.modal) {
      return;
    }

    this.modal.toggle();
  }
}
