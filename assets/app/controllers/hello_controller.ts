import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  connect() {
    this.element.textContent = 'Hello Stimulus! Edit me in assets/app/controllers/hello_controller.ts';
  }
}
