export default function RegisterUserPage() {
  return (
    <div className="p-8">
      <h1 className="text-2xl font-bold">Register User</h1>
      <form className="mt-4 space-y-3">
        <div>
          <label className="block text-sm font-medium">Name</label>
          <input
            type="text"
            className="border rounded p-2 w-full"
            placeholder="Enter your name"
          />
        </div>
        <div>
          <label className="block text-sm font-medium">Email</label>
          <input
            type="email"
            className="border rounded p-2 w-full"
            placeholder="Enter your email"
          />
        </div>
        <div>
          <label className="block text-sm font-medium">Password</label>
          <input
            type="password"
            className="border rounded p-2 w-full"
            placeholder="Enter your password"
          />
        </div>
        <button
          type="submit"
          className="bg-blue-600 text-white px-4 py-2 rounded"
        >
          Register
        </button>
      </form>
    </div>
  );
}
