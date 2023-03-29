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
declare module 'editorjs-drag-drop';
declare module 'editorjs-inline-font-size-tool';
declare module 'editorjs-text-color-plugin';

declare module 'editorjs-html' {
  import parser from 'editorjs-html/build/app';

  export default parser;
}
