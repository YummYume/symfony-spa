import { ActionEvent, Controller } from '@hotwired/stimulus';
import { Carousel, type CarouselOptions } from 'flowbite';

import type { ValueDefinitionMap } from '@hotwired/stimulus/dist/types/core/value_properties';

/* stimulusFetch: 'lazy' */
export default class CarouselController extends Controller<HTMLElement> {
  static values: ValueDefinitionMap = {
    options: { type: Object, default: {} },
  };

  static targets = ['carousel', 'item'];

  declare optionsValue: CarouselOptions;

  declare readonly hasOptionsValue: boolean;

  declare readonly carouselTarget: HTMLElement;

  declare readonly hasCarouselTarget: boolean;

  declare readonly itemTarget: HTMLElement;

  declare readonly itemTargets: HTMLElement[];

  declare readonly hasItemTarget: boolean;

  private carousel: Carousel | null = null;

  connect() {
    this.carousel = new Carousel(this.itemTargets.map((item, position) => ({ el: item, position })), this.optionsValue);
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
}
