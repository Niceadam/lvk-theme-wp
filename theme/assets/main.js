import "./main.css";
import Alpine from 'alpinejs'
import persist from '@alpinejs/persist'

Alpine.plugin(persist)

window.Alpine = Alpine
window.Alpine.start()
