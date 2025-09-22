import type { Updater } from "@tanstack/vue-table"
import type { ClassValue } from "clsx"
import type { Ref } from "vue"
import { clsx } from "clsx"
import { twMerge } from "tailwind-merge"
import { useToast } from "@/components/ui/toast/use-toast"
import { Icon } from '@iconify/vue'
import { h } from "vue"
type ToastType = 'success' | 'error' | 'info'

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs))
}

export function valueUpdater<T extends Updater<any>>(updaterOrValue: T, ref: Ref) {
    ref.value
        = typeof updaterOrValue === "function"
            ? updaterOrValue(ref.value)
            : updaterOrValue
}


export const toast = (message: string, type: ToastType = 'info') => {
    const { toast: addToast } = useToast()

    const typeMap: Record<ToastType, { bg: string; icon: string; text: string }> = {
        success: { bg: 'bg-success', icon: 'mdi:check-circle', text: 'text-white' },
        error: { bg: 'bg-error', icon: 'mdi:alert-circle', text: 'text-white' },
        info: { bg: 'bg-info', icon: 'mdi:information', text: 'text-white' },
    }

    const { bg, icon, text } = typeMap[type]

    addToast({
        duration: 3000,
        class: `${bg} ${text} rounded-md shadow-lg px-4 py-2 w-auto`,
        action: () =>
            h(
                'div',
                { class: `flex items-center justify-start w-full gap-2 ${bg} ${text}` },
                [
                    h(Icon, { icon, class: `w-5 h-5 ${text}` }),
                    h('span', { class: 'font-medium capitalize' }, message),
                ]
            ),
    })
}
export function storage(url?:any): string {
    if (!url) return ''

    // Kiểm tra nếu đã là absolute url (http/https)
    if (/^https?:\/\//.test(url)) {
        return url
    }

    // Nếu bắt đầu bằng /storage rồi thì giữ nguyên
    if (url.startsWith('/storage')) {
        return url
    }

    // Ngược lại thì prepend /storage
    return `/storage/${url.replace(/^\/+/, '')}`
}

export const generateSlug = (str: string) => {
    str = str.toLowerCase();

    // xóa dấu
    str = str
        .normalize('NFD') // chuyển chuỗi sang unicode tổ hợp
        .replace(/[\u0300-\u036f]/g, ''); // xóa các ký tự dấu sau khi tách tổ hợp

    // Thay ký tự đĐ
    str = str.replace(/[đĐ]/g, 'd');

    // Xóa ký tự đặc biệt
    str = str.replace(/([^0-9a-z-\s])/g, '');

    // Xóa khoảng trắng thay bằng ký tự -
    str = str.replace(/(\s+)/g, '-');

    // Xóa ký tự - liên tiếp
    str = str.replace(/-+/g, '-');

    // xóa phần dư - ở đầu & cuối
    str = str.replace(/^-+|-+$/g, '');

    // return
    return str;
}



export const formatCurrency = (number: string | number | bigint): string => {
  if (number === null || number === undefined) {
    number = 0;
  }
  const value = Number(number);
  return new Intl.NumberFormat('vi-VN', {
    style: 'currency',
    currency: 'VND',
    minimumFractionDigits: 0,
  }).format(value);
};
