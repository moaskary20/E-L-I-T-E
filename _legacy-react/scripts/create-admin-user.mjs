/**
 * One-time script: create (or reset) the clinic portal admin user in Supabase Auth.
 *
 * Usage:
 *   SUPABASE_SERVICE_ROLE_KEY=eyJ... \
 *   VITE_SUPABASE_URL=https://olocjihupxnaurfywlnv.supabase.co \
 *   node scripts/create-admin-user.mjs
 *
 * Optional:
 *   ADMIN_EMAIL=admin@elitephysioclinics.co.uk
 *   ADMIN_PASSWORD=YourSecurePassword123!
 */
import { createClient } from '@supabase/supabase-js';

const url = process.env.VITE_SUPABASE_URL || process.env.SUPABASE_URL;
const serviceKey = process.env.SUPABASE_SERVICE_ROLE_KEY;
const email = process.env.ADMIN_EMAIL || 'admin@elitephysioclinics.co.uk';
const password = process.env.ADMIN_PASSWORD || 'EliteAdmin2026!';

if (!url || !serviceKey) {
  console.error('Missing env vars. Set VITE_SUPABASE_URL and SUPABASE_SERVICE_ROLE_KEY.');
  process.exit(1);
}

const supabase = createClient(url, serviceKey, {
  auth: { autoRefreshToken: false, persistSession: false },
});

const { data: list, error: listError } = await supabase.auth.admin.listUsers();
if (listError) {
  console.error('Failed to list users:', listError.message);
  process.exit(1);
}

const existing = list.users.find((u) => u.email?.toLowerCase() === email.toLowerCase());

if (existing) {
  const { error } = await supabase.auth.admin.updateUserById(existing.id, {
    password,
    email_confirm: true,
  });
  if (error) {
    console.error('Failed to update existing user:', error.message);
    process.exit(1);
  }
  console.log('Admin user already existed — password reset successfully.');
} else {
  const { error } = await supabase.auth.admin.createUser({
    email,
    password,
    email_confirm: true,
  });
  if (error) {
    console.error('Failed to create user:', error.message);
    process.exit(1);
  }
  console.log('Admin user created successfully.');
}

console.log('');
console.log('Login credentials:');
console.log(`  Email:    ${email}`);
console.log(`  Password: ${password}`);
console.log('');
console.log('Portal: http://localhost:5173/clinic-portal/login');
