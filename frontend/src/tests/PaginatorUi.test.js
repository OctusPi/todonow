import { mount } from '@vue/test-utils';
import { describe, it, expect, vi } from 'vitest';
import PaginatorUi from '@/components/PaginatorUi.vue';
import { EyeIcon, ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/24/solid';


vi.mock('@/services/http', () => ({
  post: vi.fn(), 
}));

describe('PaginatorUi', () => {

 
  const mockData = {
    data: [1, 2, 3],
    current_page: 2,
    last_page: 5,
  };

  const createWrapper = (props = {}, modelValue = mockData) => {
    return mount(PaginatorUi, {
      props: {
        url: '/api',
        search: { query: 'example' },
        modelValue,
        ...props,
      },
    });
  };

  it('renderiza os botões de navegação e ícones', () => {
    const wrapper = createWrapper();
    expect(wrapper.findComponent(EyeIcon).exists()).toBe(true);
    expect(wrapper.findComponent(ChevronLeftIcon).exists()).toBe(true);
    expect(wrapper.findComponent(ChevronRightIcon).exists()).toBe(true);
  });

  it('desabilita o botão de retroceder na primeira página', () => {
    const wrapper = createWrapper({}, { ...mockData, current_page: 1 });
    const backButton = wrapper.find('button:disabled');
    expect(backButton.exists()).toBe(true);
    expect(backButton.text()).toBe('');
  });

  it('desabilita o botão de avançar na última página', () => {
    const wrapper = createWrapper({}, { ...mockData, current_page: 5 });
    const forwardButton = wrapper.find('button:disabled');
    expect(forwardButton.exists()).toBe(true);
    expect(forwardButton.text()).toBe('');
  });

  it('atualiza o modelo quando as propriedades mudam', async () => {
    const wrapper = createWrapper();
    await wrapper.setProps({ search: { query: 'new-query' } });
    expect(wrapper.vm.paginator.search).toEqual({ query: 'new-query' });
  });

  it('renderiza o número correto de botões de página', () => {
    const wrapper = createWrapper();
    const pageButtons = wrapper.findAll('button[aria-current="page"]');
    expect(pageButtons.length).toBe(mockData.last_page);
  });
});
