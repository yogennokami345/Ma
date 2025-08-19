interface Order {
    id: string;
    user_id: number;
    plan_id: Plan;
    payment_id: string;
    paid: boolean;
    created_at: string;
    updated_at: string;
}