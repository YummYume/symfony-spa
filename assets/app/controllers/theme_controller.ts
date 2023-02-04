import { Context, Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = [ "light", "dark" ];

  declare readonly hasDarkTarget: boolean
  declare readonly darkTarget: HTMLElement

  declare readonly hasLightTarget: boolean
  declare readonly lightTarget: HTMLElement
  
  constructor (context: Context) {
    super(context);
  }

  connect() {
    this.element.addEventListener("turbo:before-visit", this.setTheme.bind(this));
  }

  disconnect() {
    this.element.removeEventListener("turbo:before-visit", this.setTheme.bind(this));
  }

  private setTheme(e: any) {
    e.preventDefault();

    const urlLength = e.detail.url.split('/').length;
    const cookieValue = this.getCookie('theme_mode');

    let mode = e.detail.url.split('/')[urlLength - 1];

    if (!cookieValue) {
      if (mode === 'dark' || mode === 'light') {
        document.cookie = `theme_mode=${mode};SameSite=None; Secure; path=/`;
      }
    } else {
      mode = cookieValue === 'light' ? 'dark' : 'light'  
      document.cookie = `theme_mode=${mode};SameSite=None; Secure; path=/`;
    }

    if (this.hasLightTarget && this.hasDarkTarget) {
      switch(mode) {
        case 'dark':
            this.lightTarget.classList.contains('swap-on') ? this.lightTarget.classList.replace('swap-on', 'swap-off') : null;
            this.darkTarget.classList.contains('swap-off') ? this.darkTarget.classList.replace('swap-off', 'swap-on') : null;
          break;
        case 'light':
            this.lightTarget.classList.contains('swap-off') ? this.lightTarget.classList.replace('swap-off', 'swap-on') : null;
            this.darkTarget.classList.contains('swap-on') ? this.darkTarget.classList.replace('swap-on', 'swap-off') : null;
          break;
      }
    }
  }

  private getCookie (name: string): string|null {
    const nameEQ = name + "=";
    const cookies = document.cookie.split(";");
    let result: string|null = null;

    cookies.forEach((cookie) => {
      while (cookie.charAt(0) === " ") {
        cookie = cookie.substring(1, cookie.length);
      }
      if (cookie.indexOf(nameEQ) === 0) {
        result = cookie.substring(nameEQ.length, cookie.length);
      }
    });

    return result;
  }
}
