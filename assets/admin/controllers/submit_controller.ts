import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller<HTMLFormElement> {
  submit() {
    this.element.requestSubmit();
  }
}
