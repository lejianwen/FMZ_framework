import Vue from 'vue'
import Clipboard from 'clipboard'

function clipboardSuccess() {
  Vue.prototype.$message({
    message: 'Copy successfully',
    type: 'success',
    duration: 1500
  })
}

function clipboardError() {
  Vue.prototype.$message({
    message: 'Copy failed',
    type: 'error'
  })
}

export default function handleClipboard(text, event) {
  const clipboard = new Clipboard(event.target, {
    text: () => text
  })
  clipboard.on('success', () => {
    clipboardSuccess()
    clipboard.destroy()
  })
  clipboard.on('error', () => {
    clipboardError()
    clipboard.destroy()
  })
  clipboard.onClick(event)
}
export function copyImage(targetNode) {
  if (window.getSelection) {
    // chrome等主流浏览器
    var selection = window.getSelection()
    selection.removeAllRanges()
    var range = document.createRange()
    range.selectNode(targetNode)
    selection.addRange(range)
  } else if (document.body.createTextRange) {
    // ie
    const range = document.body.createTextRange()
    range.moveToElementText(targetNode)
    range.select()
  }
  document.execCommand('copy')
}
