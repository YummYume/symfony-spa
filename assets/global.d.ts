/* eslint-disable max-classes-per-file */
declare module 'stimulus-notification' {
  import type { ControllerConstructor } from '@hotwired/stimulus';

  const content: ControllerConstructor;

  export default content;
}

declare module '@symfony/stimulus-bridge/lazy-controller-loader?lazy=true!*' {
  import type { ControllerConstructor } from '@hotwired/stimulus';

  const content: ControllerConstructor;

  export default content;
}

declare module 'flowbite/dist/flowbite.turbo' {
  export * from 'flowbite';
}

declare module '@editorjs/*';
declare module 'editorjs-change-case';
declare module 'editorjs-inline-font-size-tool';
declare module 'editorjs-text-color-plugin';

declare module 'editorjs-undo' {
  import type EditorJS from '@editorjs/editorjs';

  class Undo {
    constructor(params: { editor: EditorJS });

    initialize: (data: EditorJS.OutputData | undefined) => void;
  }

  export default Undo;
}

declare module 'editorjs-drag-drop' {
  import type EditorJS from '@editorjs/editorjs';

  class DragDrop {
    constructor(editor: EditorJS);
  }

  export default DragDrop;
}

declare module 'editorjs-html' {
  import parser from 'editorjs-html/build/app';

  export default parser;
}
