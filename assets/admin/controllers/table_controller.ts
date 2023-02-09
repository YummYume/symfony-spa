import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = ['route', 'message'];

  declare readonly hasRouteTarget: boolean;

  declare readonly routeTarget: HTMLLinkElement;

  declare readonly hasMessageTarget: boolean;

  declare readonly messageTarget: HTMLElement;

  private async switch(e: any): Promise<void> {
    await fetch(`${window.origin}${e.target?.dataset.route}`);
  }

  private action(e: Event) {
    const target = e.target as HTMLElement;
    let el: HTMLElement|null = target;
    
    if(target.localName === 'svg') {
      el = target.parentElement;
    }

    if(el) {
      const { route, message } = el.dataset;
      if (message) {
        this.messageTarget.textContent = message;
      }
  
      if (route) {
        this.routeTarget.href = (`${window.origin}${route}`);
      }
    }
  }
}
