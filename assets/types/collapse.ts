import type { CollapseInterface } from 'flowbite/dist/flowbite.turbo';

export interface CollapseEventDetails {
  collapse: CollapseInterface | null;
}

export type CollapseCollapseEvent = CustomEvent<CollapseEventDetails>;

export type CollapseExpandEvent = CustomEvent<CollapseEventDetails>;

export type CollapseToggleEvent = CustomEvent<CollapseEventDetails>;
