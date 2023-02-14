import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = ['modal'];

  static classes = ['visible'];

  declare readonly modalTarget: HTMLElement;

  declare readonly hasModalTarget: boolean;

  declare readonly visibleClass: string;

  declare readonly hasVisibleClass: boolean;

  open() {
    if (!this.hasVisibleClass) {
      return;
    }

    const modal = this.hasModalTarget ? this.modalTarget : this.element;

    modal.classList.add(this.visibleClass);
  }

  close() {
    if (!this.hasVisibleClass) {
      return;
    }

    const modal = this.hasModalTarget ? this.modalTarget : this.element;

    modal.classList.remove(this.visibleClass);
  }
}
