export interface PortfolioProfile {
    id?: number;
    name: string;
    headline: string;
    tagline?: string | null;
    hero_intro?: string | null;
    now_building?: string | null;
    now_learning?: string | null;
    now_cooking?: string | null;
    bio: string;
    working_style?: string | null;
    education?: string | null;
    outside_the_code_html?: string | null;
    location?: string | null;
    email: string;
    phone?: string | null;
    website_url?: string | null;
    github_url?: string | null;
    linkedin_url?: string | null;
    available_for_work: boolean;
    years_experience?: number | null;
    resume_url?: string | null;
    logo_url?: string | null;
    headshot_url?: string | null;
    about_image_url?: string | null;
    favicon_url?: string | null;
}

export interface PortfolioProject {
    id: number;
    title: string;
    slug: string;
    summary: string;
    description?: string | null;
    case_study_html?: string | null;
    category?: string | null;
    organization?: string | null;
    problem?: string | null;
    role?: string | null;
    approach?: string | null;
    challenges?: string | null;
    results?: string | null;
    technologies?: string[] | null;
    live_url?: string | null;
    source_url?: string | null;
    image_url?: string | null;
    card_image_url?: string | null;
    is_featured: boolean;
    is_published?: boolean;
    sort_order: number;
}

export interface ProjectFormValues {
    id?: number;
    title: string;
    slug?: string;
    summary: string;
    description?: string | null;
    case_study_html?: string | null;
    category?: string | null;
    organization?: string | null;
    problem?: string | null;
    role?: string | null;
    approach?: string | null;
    challenges?: string | null;
    results?: string | null;
    technologies: string;
    live_url?: string | null;
    source_url?: string | null;
    image_url?: string | null;
    card_image_url?: string | null;
    is_featured: boolean;
    is_published?: boolean;
    sort_order: number;
}

export interface PortfolioSkill {
    id: number;
    name: string;
    category: string;
    proficiency?: number | null;
    sort_order: number;
}

export interface SkillGroup {
    category: string;
    skills: PortfolioSkill[];
}

export interface PortfolioExperiencePosition {
    id: number;
    company: string;
    role: string;
    location?: string | null;
    start_date: string;
    end_date?: string | null;
    is_current: boolean;
    sort_order: number;
}

export interface PortfolioExperience extends PortfolioExperiencePosition {
    id: number;
    company: string;
    role: string;
    location?: string | null;
    employment_type?: string | null;
    start_date: string;
    end_date?: string | null;
    is_current: boolean;
    description: string;
    skill_tags?: string[] | null;
    sort_order: number;
}

export interface ExperienceFormValues extends Omit<PortfolioExperience, 'skill_tags'> {
    skill_tags: string;
}

export interface ContactMessage {
    id: number;
    name: string;
    email: string;
    subject?: string | null;
    message: string;
    read_at?: string | null;
    created_at: string;
}
