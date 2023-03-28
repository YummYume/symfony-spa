import type { TabsInterface } from 'flowbite';

export interface TabsEventDetails {
  tabs: TabsInterface | null;
}

export type TabsShowEvent = CustomEvent<TabsEventDetails>;
