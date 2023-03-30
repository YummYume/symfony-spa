import { type OutputData } from '@editorjs/editorjs';
import { Controller } from '@hotwired/stimulus';
import { ValueDefinitionMap } from '@hotwired/stimulus/dist/types/core/value_properties';
import edjsHTMl from 'editorjs-html';

export default class EditorParserController extends Controller<HTMLElement> {
  static values: ValueDefinitionMap = {
    data: Object,
  };

  static targets = ['loading', 'content'];

  declare dataValue: OutputData;

  declare readonly hasDataValue: boolean;

  declare readonly loadingTarget: HTMLElement;

  declare readonly hasLoadingTarget: boolean;

  declare readonly contentTarget: HTMLElement;

  declare readonly hasContentTarget: boolean;

  connect() {
    if (!this.hasDataValue || !this.hasContentTarget) {
      return;
    }

    const html = this.jsonToHtml(this.dataValue);

    this.contentTarget.innerHTML = html;

    if (this.hasLoadingTarget) {
      this.loadingTarget.remove();
    }
  }

  private jsonToHtml(value: OutputData): string {
    return edjsHTMl().parse(value).reduce((acc, curr) => acc + curr, '');
  }
}
