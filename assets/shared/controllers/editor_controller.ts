/* eslint-disable @typescript-eslint/no-unsafe-assignment */
import Delimiter from '@editorjs/delimiter';
import EditorJS, { OutputData } from '@editorjs/editorjs';
import Header from '@editorjs/header';
import NestedList from '@editorjs/nested-list';
import Paragraph from '@editorjs/paragraph';
import Quote from '@editorjs/quote';
import SimpleImage from '@editorjs/simple-image';
import TextVariantTune from '@editorjs/text-variant-tune';
import Underline from '@editorjs/underline';
import { Controller } from '@hotwired/stimulus';
import ChangeCase from 'editorjs-change-case';
import DragDrop from 'editorjs-drag-drop';
import edjsHTML from 'editorjs-html';
import FontSize from 'editorjs-inline-font-size-tool';
import ColorPlugin from 'editorjs-text-color-plugin';

export default class EditorController extends Controller<HTMLElement> {
  private editor: EditorJS | null = null;

  static targets = ['input'];

  declare readonly inputTarget: HTMLElement;

  declare readonly hasInputTarget: boolean;

  connect() {
    this.editor = new EditorJS({
      // eslint-disable-next-line @typescript-eslint/no-unsafe-call, @typescript-eslint/no-unsafe-return
      onReady: () => new DragDrop(this.editor),
      onChange: (api, event) => {
        this.dispatch('editor:change', {
          target: this.element,
          detail: { api, event },
        });
      },
      holder: 'editor',
      placeholder: 'Write anything ;)',
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
          config: {
            quotePlaceholder: 'Enter a quote',
            captionPlaceholder: 'Quote\'s author',
          },
        },
        delimiter: Delimiter,
        paragraph: {
          class: Paragraph,
          inlineToolbar: true,
          tunes: ['textVariant'],
        },
      },
      i18n: {
        messages: {
          ui: {
            blockTunes: {
              textVariant: {
                'Call-out': 'Appel',
                Citation: 'Citation',
                Details: 'Détails',
              },
              toggler: {
                'Click to tune': 'Cliquer pour customiser ou deplacer',
              },
            },
            inlineToolbar: {
              converter: {
                'Convert to': 'Convertir vers',
              },
            },
            toolbar: {
              toolbox: {
                Add: 'Ajouter',
              },
            },
          },
          toolNames: {
            Text: 'Texte',
            Warning: 'Avertissement',
            Quote: 'Citation',
            List: 'Liste',
            SimpleImage: 'Image',
            Color: 'Couleur',
            Underline: 'Souligner',
            Heading: 'En-tête',
            Delimiter: 'Délimiteur',
            Bold: 'Gras',
            Italic: 'Italique',
            Link: 'Lien',
            ChangeCase: 'Changer casse',
            'Font Size': 'Taille police',
          },
          tools: {
            link: {
              'Add a link': 'Ajoutée un lien',
            },
          },
        },
      },
    });
  }

  async save() {
    if (!this.editor || !this.hasInputTarget || !(this.inputTarget instanceof HTMLInputElement)) {
      return;
    }

    const savedData = await this.editor.save();

    this.inputTarget.value = JSON.stringify(savedData);
  }

  jsonToHtml(value: OutputData): string {
    return edjsHTML().parse(value).reduce((acc, curr) => acc + curr, '');
  }

  disconnect(): void {
    this.editor?.destroy();
  }
}
