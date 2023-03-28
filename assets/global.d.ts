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
