import { Controller } from '@hotwired/stimulus';
import * as focusTrap from 'focus-trap';

/* stimulusFetch: 'lazy' */
export default class extends Controller<HTMLElement> {
  static targets = ['modal', 'initialFocus'];

  static classes = ['visible'];

  declare readonly modalTarget: HTMLElement;

  declare readonly hasModalTarget: boolean;

  declare readonly initialFocusTarget: HTMLElement;

  declare readonly hasInitialFocusTarget: boolean;

  declare readonly visibleClass: string;

  declare readonly hasVisibleClass: boolean;

  private modal: HTMLElement = this.element;

  private focusTrap?: focusTrap.FocusTrap;

  connect() {
    if (this.hasModalTarget) {
      this.modal = this.modalTarget;
    }

    this.focusTrap = focusTrap.createFocusTrap(this.modal, {
      initialFocus: this.hasInitialFocusTarget ? this.initialFocusTarget : undefined,
      fallbackFocus: this.hasInitialFocusTarget ? this.initialFocusTarget : undefined,
    });
  }

  disconnect() {
    this.focusTrap?.deactivate();
  }

  open() {
    if (!this.hasVisibleClass) {
      return;
    }

    this.modal.classList.add(this.visibleClass);
    this.focusTrap?.activate({
      checkCanFocusTrap: () => new Promise((resolve, reject) => {
        if (!this.hasInitialFocusTarget) {
          reject();
        }

        let retries = 0;

        const interval = setInterval(() => {
          if (retries > 10) {
            clearInterval(interval);
            reject();
          } else if (this.initialFocusTarget.offsetParent !== null) {
            clearInterval(interval);
            resolve();
          }

          retries += 1;
        }, 100);
      }),
    });
  }

  close() {
    if (!this.hasVisibleClass) {
      return;
    }

    this.focusTrap?.deactivate();
    this.modal.classList.remove(this.visibleClass);
  }
}
