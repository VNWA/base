interface NavItemChild {
    label: string
    icon?: string
    to: string
}
export interface NavItem {
    label: string
    icon?: string
    to?: string
    childs?: NavItemChild[]
}
export interface ItemConfirm {
    value: string | number;
    label: string;
}
export interface Meta {
    title: string,
    desc: string
    image: string,
}

export interface ProductCategory {
    id?: number | null;
    parent_id?: number | null;
    ord?: number;
    url_avatar: string;
    name: string;
    slug: string;
    description?: string;
    content?: string;
    meta: Meta
    created_at?: string; // hoặc Date nếu bạn convert khi fetch
    updated_at?: string; // hoặc Date
}
export interface ProductBrand {
    id?: number | null;
    ord?: number;
    url_avatar: string;
    name: string;
    slug: string;
    description: string;
    meta: Meta
    created_at?: string; // hoặc Date nếu bạn convert khi fetch
    updated_at?: string; // hoặc Date
}
export interface Media {
    avatar: string;
    type: 'file' | 'dir';
    file_type?: 'video' | 'image' | 'document' | 'other';
    path: string;
    size?: number;
    mimeType?: string;
    lastModified?: string;
    name: string;
}


export interface ProductAppend {
    delivery_and_returns: string;
    specification: string
}
export interface Product {
    id?: number;
    brand_id: number | null
    category_ids: number[]
    url_avatar: string;
    images: string[];
    sku: string
    stock: number
    origin: string
    name: string
    slug: string
    description?: string
    append: ProductAppend
    price: number
    price_old: number
    meta: Meta
    created_at?: string; // hoặc Date nếu bạn convert khi fetch
    updated_at?: string; // hoặc Date
}
export interface StaticPage {
    id?: number | null;
    image?: string;
    name: string;
    slug: string;
    desc?: string;
    content?: string;
    meta: Meta
    created_at?: string;
    updated_at?: string;
}

export interface Button {
    label: string;
    to: string;
    style: string
}
export interface Banner {
    image: string;
    title: string;
    description: string;
    buttons: Button[];
}