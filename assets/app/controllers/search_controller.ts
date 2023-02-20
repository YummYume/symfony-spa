import { ApplicationController, useDebounce } from 'stimulus-use';

/* stimulusFetch: 'lazy' */
export default class extends ApplicationController {
  static debounces = ['submit'];

  connect() {
    useDebounce(this, { wait: 200 });
  }

  submit() {
    if (this.element instanceof HTMLFormElement) {
      this.element.requestSubmit();
    }
  }
}
