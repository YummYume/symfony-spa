import type { CollapseInterface } from 'flowbite/lib/esm';

export interface CollapseEventDetails {
  collapse: CollapseInterface;
}

export type CollapseCollapseEvent = CustomEvent<CollapseEventDetails>;

export type CollapseExpandEvent = CustomEvent<CollapseEventDetails>;

export type CollapseToggleEvent = CustomEvent<CollapseEventDetails>;
