import { ActionEvent, Controller } from '@hotwired/stimulus';
import { Carousel, type CarouselOptions, type CarouselInterface } from 'flowbite';

import { CAROUSEL_EVENTS } from '$assets/types/constants/carousel';

import type { ValueDefinitionMap } from '@hotwired/stimulus/dist/types/core/value_properties';

/* stimulusFetch: 'lazy' */
export default class CarouselController extends Controller<HTMLElement> {
  static values: ValueDefinitionMap = {
    options: { type: Object, default: {} },
    eventPrefix: String,
  };

  static targets = ['item'];

  declare optionsValue: CarouselOptions;

  declare readonly hasOptionsValue: boolean;

  declare eventPrefixValue: string;

  declare readonly hasEventPrefixValue: boolean;

  declare readonly itemTarget: HTMLElement;

  declare readonly itemTargets: HTMLElement[];

  declare readonly hasItemTarget: boolean;

  private eventPrefix: string | undefined = undefined;

  private carousel: CarouselInterface | null = null;

  connect() {
    this.eventPrefix = this.hasEventPrefixValue ? this.eventPrefixValue : undefined;
    this.carousel = new Carousel(
      this.itemTargets.map((item, position) => ({ el: item, position })),
      { ...this.defaultValues, ...this.optionsValue },
    );

    document.addEventListener('turbo:before-cache', this.beforeCache);
  }

  disconnect() {
    document.removeEventListener('turbo:before-cache', this.beforeCache);
  }

  next() {
    if (!this.carousel) {
      return;
    }

    this.carousel.next();
  }

  prev() {
    if (!this.carousel) {
      return;
    }

    this.carousel.prev();
  }

  slideTo({ params }: ActionEvent) {
    if (!this.carousel) {
      return;
    }

    const slideTo = typeof params.slideTo === 'number' ? params.slideTo : 0;

    this.carousel.slideTo(slideTo);
  }

  pause() {
    if (!this.carousel) {
      return;
    }

    this.carousel.pause();
  }

  cycle() {
    if (!this.carousel) {
      return;
    }

    this.carousel.cycle();
  }

  isVisible(position: number) {
    if (!this.carousel) {
      return false;
    }

    return this.carousel.getItem(position);
  }

  isInitialized() {
    return !!this.carousel;
  }

  private beforeCache = () => {
    if (!this.carousel) {
      return;
    }

    this.carousel.slideTo(0);
  };

  get defaultValues(): CarouselOptions {
    return {
      onNext: () => {
        this.dispatch(CAROUSEL_EVENTS.NEXT, {
          target: this.element,
          detail: { carousel: this.carousel },
          prefix: this.eventPrefix,
        });
      },
      onPrev: () => {
        this.dispatch(CAROUSEL_EVENTS.PREV, {
          target: this.element,
          detail: { carousel: this.carousel },
          prefix: this.eventPrefix,
        });
      },
      onChange: () => {
        this.dispatch(CAROUSEL_EVENTS.CHANGE, {
          target: this.element,
          detail: { carousel: this.carousel },
          prefix: this.eventPrefix,
        });
      },
    };
  }
}
