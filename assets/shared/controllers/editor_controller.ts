/* eslint-disable @typescript-eslint/no-unsafe-assignment */
import Delimiter from '@editorjs/delimiter';
import EditorJS, { type EditorConfig } from '@editorjs/editorjs';
import Header from '@editorjs/header';
import NestedList from '@editorjs/nested-list';
import Paragraph from '@editorjs/paragraph';
import Quote from '@editorjs/quote';
import SimpleImage from '@editorjs/simple-image';
import TextVariantTune from '@editorjs/text-variant-tune';
import Underline from '@editorjs/underline';
import { Controller } from '@hotwired/stimulus';
import { ValueDefinitionMap } from '@hotwired/stimulus/dist/types/core/value_properties';
import merge from 'deepmerge';
import ChangeCase from 'editorjs-change-case';
import DragDrop from 'editorjs-drag-drop';
import FontSize from 'editorjs-inline-font-size-tool';
import ColorPlugin from 'editorjs-text-color-plugin';
import Undo from 'editorjs-undo';
import { useDebounce } from 'stimulus-use';

import { EDITOR_EVENTS } from '$assets/types/constants/editor';

export default class EditorController extends Controller<HTMLElement> {
  static values: ValueDefinitionMap = {
    options: { type: Object, default: {} },
    debounce: { type: Number, default: 1000 },
    eventPrefix: String,
  };

  static targets = ['editor', 'input'];

  static debounces = ['save'];

  declare optionsValue: EditorConfig;

  declare readonly hasOptionsValue: boolean;

  declare debounceValue: number;

  declare readonly hasDebounceValue: boolean;

  declare eventPrefixValue: string;

  declare readonly hasEventPrefixValue: boolean;

  declare readonly editorTarget: HTMLElement;

  declare readonly hasEditorTarget: boolean;

  declare readonly inputTarget: HTMLElement;

  declare readonly hasInputTarget: boolean;

  private editor: EditorJS | null = null;

  private undo: Undo | null = null;

  private dragDrop: DragDrop | null = null;

  private options: EditorConfig = this.defaultOptions;

  connect() {
    useDebounce(this, {
      wait: this.debounceValue,
      name: this.hasEventPrefixValue ? this.eventPrefixValue : undefined,
    });

    this.options = merge(this.defaultOptions, this.optionsValue, {
      clone: false,
    });
    this.editor = new EditorJS(this.options);
  }

  disconnect() {
    if (!this.editor) {
      return;
    }

    this.editor.destroy();
  }

  async save() {
    if (!this.editor || !this.hasInputTarget || !(this.inputTarget instanceof HTMLInputElement)) {
      return;
    }

    const savedData = await this.editor.save();

    this.inputTarget.value = JSON.stringify(savedData);
  }

  get defaultOptions(): EditorConfig {
    return {
      onReady: () => {
        this.dispatch(EDITOR_EVENTS.READY, {
          target: this.hasEditorTarget ? this.editorTarget : this.element,
          detail: { editor: this.editor },
          prefix: this.hasEventPrefixValue ? this.eventPrefixValue : undefined,
        });

        if (!this.editor) {
          return;
        }

        this.undo = new Undo({ editor: this.editor });
        this.dragDrop = new DragDrop(this.editor);

        if (this.options.data) {
          this.undo.initialize(this.options.data);
        }
      },
      onChange: (api, event) => {
        this.dispatch(EDITOR_EVENTS.CHANGE, {
          target: this.hasEditorTarget ? this.editorTarget : this.element,
          detail: { editor: this.editor, api, event },
          prefix: this.hasEventPrefixValue ? this.eventPrefixValue : undefined,
        });
      },
      holder: this.hasEditorTarget ? this.editorTarget : this.element,
      tools: {
        textVariant: TextVariantTune,
        Color: {
          class: ColorPlugin,
          config: {
            defaultColor: '#FF1300',
            type: 'text',
            customPicker: true,
          },
        },
        underline: Underline,
        changeCase: ChangeCase,
        image: SimpleImage,
        header: Header,
        fontSize: FontSize,
        list: {
          class: NestedList,
          inlineToolbar: true,
          config: {
            defaultStyle: 'unordered',
          },
        },
        quote: {
          class: Quote,
          inlineToolbar: true,
          shortcut: 'CMD+SHIFT',
        },
        delimiter: Delimiter,
        paragraph: {
          class: Paragraph,
          inlineToolbar: true,
          tunes: ['textVariant'],
        },
      },
    };
  }
}
