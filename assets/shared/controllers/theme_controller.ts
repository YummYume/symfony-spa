import { Controller } from '@hotwired/stimulus';

import type { TurboBeforeVisitEvent } from '@hotwired/turbo';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = ['light', 'dark'];

  static classes = ['darkMode'];

  declare readonly darkTarget: HTMLElement;

  declare readonly hasDarkTarget: boolean;

  declare readonly lightTarget: HTMLElement;

  declare readonly hasLightTarget: boolean;

  declare readonly darkModeClass: string;

  declare readonly darkModeClasses: string[];

  declare readonly hasDarkModeClass: boolean;

  connect() {
    this.element.addEventListener('turbo:before-visit', this.setTheme.bind(this));
  }

  disconnect() {
    this.element.removeEventListener('turbo:before-visit', this.setTheme.bind(this));
  }

  private setTheme(e: TurboBeforeVisitEvent): void {
    const urlSplit = e.detail.url.split('/');
    const urlLength = urlSplit.length;

    if (urlSplit.includes('dark') || urlSplit.includes('light')) {
      e.preventDefault();

      const cookieValue = this.getCookie('theme_mode');

      let mode = e.detail.url.split('/')[urlLength - 1];

      if (!cookieValue) {
        if (mode === 'dark' || mode === 'light') {
          document.cookie = `theme_mode=${mode};SameSite=None; Secure; path=/`;
        }
      } else {
        mode = cookieValue === 'light' ? 'dark' : 'light';
        document.cookie = `theme_mode=${mode};SameSite=None; Secure; path=/`;
      }

      if (this.hasDarkModeClass) {
        if (mode === 'dark') {
          this.element.classList.add(this.darkModeClasses.join(' '));
        } else {
          this.element.classList.remove(this.darkModeClasses.join(' '));
        }
      }

      if (this.hasLightTarget && this.hasDarkTarget) {
        if (mode === 'dark') {
          if (this.lightTarget.classList.contains('swap-on')) this.lightTarget.classList.replace('swap-on', 'swap-off');

          if (this.darkTarget.classList.contains('swap-off')) this.darkTarget.classList.replace('swap-off', 'swap-on');
        }

        if (mode === 'light') {
          if (this.lightTarget.classList.contains('swap-off')) {
            this.lightTarget.classList.replace('swap-off', 'swap-on');
          }

          if (this.darkTarget.classList.contains('swap-on')) this.darkTarget.classList.replace('swap-on', 'swap-off');
        }
      }
    }
  }

  private getCookie(name: string): string | null {
    const nameEQ = `${name}=`;
    const cookies = document.cookie.split(';');
    let result: string | null = null;

    cookies.forEach((cookie: string) => {
      let ck: string = cookie;

      while (ck.charAt(0) === ' ') {
        ck = ck.substring(1, ck.length);
      }

      if (ck.indexOf(nameEQ) === 0) {
        result = ck.substring(nameEQ.length, ck.length);
      }
    });

    return result;
  }
}
