import { Controller } from '@hotwired/stimulus';
import { Dismiss, type DismissOptions } from 'flowbite';

import type { ValueDefinitionMap } from '@hotwired/stimulus/dist/types/core/value_properties';

/* stimulusFetch: 'lazy' */
export default class DismissController extends Controller<HTMLElement> {
  static values: ValueDefinitionMap = {
    options: { type: Object, default: {} },
  };

  static targets = ['dismiss', 'trigger'];

  declare optionsValue: DismissOptions;

  declare readonly hasOptionsValue: boolean;

  declare readonly dismissTarget: HTMLElement;

  declare readonly hasDismissTarget: boolean;

  declare readonly triggerTarget: HTMLElement;

  declare readonly hasTriggerTarget: boolean;

  private dismiss: Dismiss | null = null;

  connect() {
    this.dismiss = new Dismiss(
      this.hasDismissTarget ? this.dismissTarget : this.element,
      this.hasTriggerTarget ? this.triggerTarget : undefined,
      this.optionsValue,
    );
  }

  hide() {
    if (!this.dismiss) {
      return;
    }

    this.dismiss.hide();
  }
}
