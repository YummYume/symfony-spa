import { Controller } from '@hotwired/stimulus';

import { AvailableThemes } from '$types/theme';
import { getCookie } from '$utils/cookies';

import type { TurboBeforeVisitEvent } from '@hotwired/turbo';

/* stimulusFetch: 'lazy' */
export default class ThemeController extends Controller {
  static targets = ['light', 'dark'];

  static classes = ['darkMode', 'lightMode', 'hidden'];

  static values = { uri: String };

  declare readonly darkTarget: HTMLElement;

  declare readonly hasDarkTarget: boolean;

  declare readonly lightTarget: HTMLElement;

  declare readonly hasLightTarget: boolean;

  declare readonly darkModeClass: string;

  declare readonly darkModeClasses: string[];

  declare readonly hasDarkModeClass: boolean;

  declare readonly lightModeClass: string;

  declare readonly lightModeClasses: string[];

  declare readonly hasLightModeClass: boolean;

  declare readonly hiddenClass: string;

  declare readonly hiddenClasses: string[];

  declare readonly hasHiddenClass: boolean;

  declare uriValue: string;

  declare readonly hasUriValue: boolean;

  connect() {
    this.element.addEventListener('turbo:before-visit', this.setTheme.bind(this));
  }

  disconnect() {
    this.element.removeEventListener('turbo:before-visit', this.setTheme.bind(this));
  }

  private setTheme(e: TurboBeforeVisitEvent): void {
    if (!this.hasUriValue || e.detail.url !== this.uriValue) {
      return;
    }

    e.preventDefault();

    const currentMode = getCookie('theme_mode');
    const targetMode: AvailableThemes = currentMode === 'dark' ? 'light' : 'dark';

    document.cookie = `theme_mode=${targetMode};SameSite=None; Secure; path=/`;

    if (this.hasDarkModeClass && this.hasLightModeClass) {
      const darkModeClasses = this.darkModeClasses.join(' ');
      const lightModeClasses = this.lightModeClasses.join(' ');

      if (targetMode === 'dark') {
        this.element.classList.remove(lightModeClasses);
        this.element.classList.add(darkModeClasses);
        this.element.setAttribute('data-theme', darkModeClasses);
      } else {
        this.element.classList.remove(darkModeClasses);
        this.element.classList.add(lightModeClasses);
        this.element.setAttribute('data-theme', lightModeClasses);
      }
    }

    if (this.hasLightTarget && this.hasDarkTarget && this.hasHiddenClass) {
      if (targetMode === 'dark') {
        this.darkTarget.classList.remove(...this.hiddenClasses);
        this.lightTarget.classList.add(...this.hiddenClasses);
      } else {
        this.lightTarget.classList.remove(...this.hiddenClasses);
        this.darkTarget.classList.add(...this.hiddenClasses);
      }
    }
  }
}
