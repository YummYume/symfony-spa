declare module 'stimulus-notification' {
  import { ControllerConstructor } from '@hotwired/stimulus';

  const content: ControllerConstructor;

  export default content;
}

declare module '@symfony/stimulus-bridge/lazy-controller-loader?lazy=true!*' {
  import { ControllerConstructor } from '@hotwired/stimulus';

  const content: ControllerConstructor;

  export default content;
}
