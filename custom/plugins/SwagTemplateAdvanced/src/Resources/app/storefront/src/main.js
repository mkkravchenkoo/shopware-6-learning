import Rumble from './scripts/ramble'
import QuantityFieldPlugin from './scripts/quantity-field.plugin'
import StickyHeader from './scripts/sticky-header'

window.PluginManager.override('AddToCart', Rumble, '[data-add-to-cart]' )
window.PluginManager.register('Quantity', QuantityFieldPlugin, '[data-quantity-field]'  )
window.PluginManager.register('StickyHeader', StickyHeader, '[data-sticky-header]', {
    showOnScrollPosition: 250
})
