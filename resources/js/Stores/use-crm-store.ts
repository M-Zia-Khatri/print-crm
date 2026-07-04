import { create } from 'zustand';

type CrmState = {
    sidebarSearch: string;
    setSidebarSearch: (value: string) => void;
};

export const useCrmStore = create<CrmState>((set) => ({
    sidebarSearch: '',
    setSidebarSearch: (value) => set({ sidebarSearch: value }),
}));
