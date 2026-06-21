import { describe, it, expect } from 'vitest'
import { mount } from '@vue/test-utils'
import BaseCard from '../../components/BaseCard.vue'

describe('BaseCard', () => {
  it('renders default slot content', () => {
    const wrapper = mount(BaseCard, {
      slots: { default: 'Card content' },
    })
    expect(wrapper.text()).toContain('Card content')
  })

  it('renders header slot', () => {
    const wrapper = mount(BaseCard, {
      slots: {
        header: 'Card Header',
        default: 'Body',
      },
    })
    expect(wrapper.text()).toContain('Card Header')
  })

  it('renders footer slot', () => {
    const wrapper = mount(BaseCard, {
      slots: {
        footer: 'Card Footer',
        default: 'Body',
      },
    })
    expect(wrapper.text()).toContain('Card Footer')
  })

  it('applies hoverable classes', () => {
    const wrapper = mount(BaseCard, {
      props: { hoverable: true },
    })
    expect(wrapper.classes()).toEqual(
      expect.arrayContaining(['hover:shadow-md'])
    )
  })

  it('applies padding classes', () => {
    const wrapper = mount(BaseCard, {
      props: { padding: 'lg' },
    })
    // padding class is on the content div, not the root
    const contentDiv = wrapper.find('div > div:not([class*="px-4"])')
    expect(wrapper.html()).toContain('p-6')
  })

  it('no padding when padding="none"', () => {
    const wrapper = mount(BaseCard, {
      props: { padding: 'none' },
    })
    expect(wrapper.html()).not.toContain('p-3')
    expect(wrapper.html()).not.toContain('p-4')
    expect(wrapper.html()).not.toContain('p-6')
  })
})
