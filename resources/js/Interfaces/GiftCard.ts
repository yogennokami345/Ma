
export interface GiftCard {
    id: number;
    code: string;
    gift_create_by_user_id: number;
    owner_user_id?: User | null;
    usage_limit: number;
    usage_count: number;
    expires_at: string;
    plan_id: Plan;
    active: boolean;
    created_at: string;
    updated_at: string;
}