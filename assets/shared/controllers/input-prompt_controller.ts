import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static values = {
    prompt: String,
    disabledOnConnect: { type: Boolean, default: true },
  };

  static targets = ['button'];

  declare promptValue: string;

  declare readonly hasPromptValue: boolean;

  declare disabledOnConnectValue: boolean;

  declare readonly hasDisabledOnConnectValue: boolean;

  declare readonly buttonTarget: HTMLButtonElement;

  declare readonly buttonTargets: HTMLButtonElement[];

  declare readonly hasButtonTarget: boolean;

  connect() {
    if (this.hasDisabledOnConnectValue && this.disabledOnConnectValue) {
      if (!this.hasButtonTarget) {
        return;
      }

      this.buttonTarget.setAttribute('disabled', '');
    }
  }

  onInput(e: InputEvent) {
    const { target } = e;

    if (!target || !(target instanceof HTMLTextAreaElement)) {
      return;
    }

    this.checkForPrompt(target.value);
  }

  checkForPrompt(value: string) {
    if (!this.hasButtonTarget || !this.hasPromptValue) {
      return;
    }

    const matches = value === this.promptValue;

    if (matches) {
      this.buttonTargets.forEach((button) => {
        button.removeAttribute('disabled');
      });

      return;
    }

    this.buttonTargets.forEach((button) => {
      button.setAttribute('disabled', '');
    });
  }
}
