import { Controller } from '@hotwired/stimulus';
import Modal from 'flowbite/lib/esm/components/modal';

import { MODAL_EVENTS } from '$types/constants/modal';

import type { ValueDefinitionMap } from '@hotwired/stimulus/dist/types/core/value_properties';
import type { ModalOptions, ModalInterface } from 'flowbite/lib/esm';

/* stimulusFetch: 'lazy' */
export default class ModalPopupController extends Controller<HTMLElement> {
  static values: ValueDefinitionMap = {
    options: { type: Object, default: {} },
    eventPrefix: String,
  };

  static targets = ['modal', 'focus'];

  declare optionsValue: ModalOptions;

  declare readonly hasOptionsValue: boolean;

  declare eventPrefixValue: string;

  declare readonly hasEventPrefixValue: boolean;

  declare readonly modalTarget: HTMLElement;

  declare readonly hasModalTarget: boolean;

  declare readonly focusTarget: HTMLElement;

  declare readonly hasFocusTarget: boolean;

  private target = this.element;

  private eventPrefix: string | undefined = undefined;

  private modal: ModalInterface | null = null;

  connect() {
    this.target = this.hasModalTarget ? this.modalTarget : this.element;
    this.eventPrefix = this.hasEventPrefixValue ? this.eventPrefixValue : undefined;
    this.modal = new Modal(this.target, { ...this.defaultOptions, ...this.optionsValue });

    document.addEventListener('turbo:before-cache', this.beforeCache);
  }

  disconnect() {
    document.removeEventListener('turbo:before-cache', this.beforeCache);
  }

  show() {
    if (!this.modal) {
      return;
    }

    this.modal.show();

    if (!this.hasFocusTarget) {
      return;
    }

    this.focusTarget.focus();
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

  isHidden() {
    if (!this.modal) {
      return true;
    }

    return this.modal.isHidden();
  }

  isVisible() {
    if (!this.modal) {
      return false;
    }

    return this.modal.isVisible();
  }

  isInitialized() {
    return !!this.modal;
  }

  private beforeCache = () => {
    if (!this.modal) {
      return;
    }

    this.modal.hide();
  };

  get defaultOptions(): ModalOptions {
    return {
      onHide: () => {
        this.dispatch(MODAL_EVENTS.HIDE, {
          target: this.target,
          detail: { modal: this.modal },
          prefix: this.eventPrefix,
        });
      },
      onShow: () => {
        this.dispatch(MODAL_EVENTS.SHOW, {
          target: this.target,
          detail: { modal: this.modal },
          prefix: this.eventPrefix,
        });
      },
      onToggle: () => {
        this.dispatch(MODAL_EVENTS.TOGGLE, {
          target: this.target,
          detail: { modal: this.modal },
          prefix: this.eventPrefix,
        });
      },
    };
  }
}
