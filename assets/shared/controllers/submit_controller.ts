import { type ActionEvent, Controller } from '@hotwired/stimulus';
import { ValueDefinitionMap } from '@hotwired/stimulus/dist/types/core/value_properties';
import { useThrottle } from 'stimulus-use';

import { findSubmitButtonsForForm } from '$utils/nodes';

import type { TurboSubmitEndEvent, TurboSubmitStartEvent } from '@hotwired/turbo';

export default class SubmitController extends Controller<HTMLElement> {
  static values: ValueDefinitionMap = {
    throttle: { type: Number, default: 1000 },
    disableOnSubmit: { type: Boolean, default: true },
  };

  static targets = ['form'];

  static throttles = ['submit'];

  declare throttleValue: number;

  declare readonly hasThrottleValue: boolean;

  declare disableOnSubmitValue: boolean;

  declare readonly hasDisableOnSubmitValue: boolean;

  declare readonly formTarget: HTMLElement;

  declare readonly formTargets: HTMLElement[];

  declare readonly hasFormTarget: boolean;

  #submitting = false;

  #forms: HTMLFormElement[] = [];

  #useSelf = false;

  connect() {
    useThrottle(this, { wait: this.throttleValue });

    if (this.element instanceof HTMLFormElement) {
      this.#forms.push(this.element);
      this.#useSelf = true;
    }

    document.documentElement.addEventListener('turbo:submit-start', this.onSubmitStart as EventListener);
    document.documentElement.addEventListener('turbo:submit-end', this.onSubmitEnd as EventListener);
  }

  disconnect() {
    document.documentElement.removeEventListener('turbo:submit-start', this.onSubmitStart as EventListener);
    document.documentElement.removeEventListener('turbo:submit-end', this.onSubmitEnd as EventListener);
  }

  submit({ params }: ActionEvent) {
    if (this.#submitting) {
      return;
    }

    const isRequestSubmit = typeof params.isRequestSubmit === 'boolean' ? params.isRequestSubmit : true;

    this.#forms.forEach((form) => {
      if (isRequestSubmit) {
        form.requestSubmit();
      } else {
        form.submit();
      }
    });
  }

  private onSubmitStart = (event: TurboSubmitStartEvent) => {
    const form = event.detail.formSubmission.formElement;

    if (this.#submitting || !this.#forms.includes(form)) {
      return;
    }

    this.#submitting = true;

    if (this.disableOnSubmitValue) {
      const submitButtons = findSubmitButtonsForForm(form);

      submitButtons.forEach((button) => {
        button.setAttribute('disabled', '');
      });
    }
  };

  private onSubmitEnd = (event: TurboSubmitEndEvent) => {
    const form = event.detail.formSubmission.formElement;

    if (!this.#submitting || !this.#forms.includes(form)) {
      return;
    }

    this.#submitting = false;

    if (this.disableOnSubmitValue) {
      const submitButtons = findSubmitButtonsForForm(form);

      submitButtons.forEach((button) => {
        button.removeAttribute('disabled');
      });
    }
  };

  formTargetConnected(element: HTMLElement) {
    if (!(element instanceof HTMLFormElement) || this.#useSelf) {
      return;
    }

    this.#forms.push(element);
  }

  formTargetDisconnected(element: HTMLElement) {
    if (!(element instanceof HTMLFormElement) || this.#useSelf) {
      return;
    }

    const index = this.#forms.indexOf(element);

    if (index === -1) {
      return;
    }

    this.#forms = this.#forms.splice(index, 1);
  }
}
