import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static values = {
    prompt: String,
    buttonSelector: String,
    disabledOnConnect: { type: Boolean, default: true },
  };

  declare promptValue: string;

  declare readonly hasPromptValue: boolean;

  declare buttonSelectorValue: string;

  declare readonly hasButtonSelectorValue: boolean;

  declare disabledOnConnectValue: boolean;

  declare readonly hasDisabledOnConnectValue: boolean;

  connect() {
    if (this.hasDisabledOnConnectValue && this.disabledOnConnectValue) {
      const button = this.element.querySelector(this.buttonSelectorValue);

      if (!button) {
        return;
      }

      button.setAttribute('disabled', '');
    }
  }

  onInput(e: InputEvent) {
    const { target } = e;

    if (!target || !(target instanceof HTMLInputElement)) {
      return;
    }

    this.checkForPrompt(target.value);
  }

  checkForPrompt(value: string) {
    const button = this.hasButtonSelectorValue ? this.element.querySelector(this.buttonSelectorValue) : null;

    if (!button || !this.hasPromptValue) {
      return;
    }

    const matches = value === this.promptValue;

    if (matches) {
      button.removeAttribute('disabled');

      return;
    }

    button.setAttribute('disabled', '');
  }
}
