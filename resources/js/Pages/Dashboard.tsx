import { useState } from "react";
import { Sidebar } from "@/components/sidebar";
import { QuestionCard } from "../components/question-card";
import { RaiseIssueModal } from "../components/raise-issue-modal";
import { AdminPostModal } from "../components/admin-post-modal";
import { Input } from "@/components/ui/input";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { Button } from "@/components/ui/button";
import { Card, CardContent } from "@/components/ui/card";
import { Search, Plus, Users, CheckCircle, Hourglass, TrendingUp } from "lucide-react";
import { usePage } from "@inertiajs/react";
import '../../css/solves.css';

export interface User {
  id: number;
  first_name?: string;
  last_name?: string;
  username: string;
  role: string;
}

export interface QuestionData {
  id: string;
  title: string;
  description: string;
  category: 'ibml' | 'softtrac' | 'omniscan';
  priority: 'low' | 'medium' | 'high' | 'urgent';
  created_by: string;
  created_at: string;
  status: 'pending' | 'approved' | 'rejected';
  views: number;
  is_final: boolean;
  attachment?: string | null;
  author?: User;
}

export interface PageProps {
  solves: {
    user: User | null;
  };
  questions?: QuestionData[];
  [key: string]: unknown;
  userCount:number;
  pendingApprovals:number
}

export default function Dashboard() {
  const { solves, questions = [] , userCount , pendingApprovals = 0} = usePage<PageProps>().props;
  const user = solves?.user;

  // State management for modals
  const [showRaiseIssueModal, setShowRaiseIssueModal] = useState(false);
  const [showAdminPostModal, setShowAdminPostModal] = useState(false);

  console.log(questions)

  return (
    <div className="min-h-screen bg-background">
      {/* Optional: Pass handler to Sidebar if Sidebar triggers modal as well */}
      <Sidebar />

      {/* Main Content */}
      <main className="ml-64 min-h-screen">
        {/* Header */}
        <header className="bg-card border-b border-border p-6">
          <div className="flex items-center justify-between">
            <div className="flex items-center space-x-4">
              <h2 className="text-2xl font-bold text-foreground">Dashboard</h2>
              <div className="flex items-center space-x-2">
                <span className="text-sm text-muted-foreground">Welcome back,</span>
                <span className="text-sm font-medium text-primary">
                  {user?.first_name && user?.last_name
                    ? `${user.first_name} ${user.last_name}`
                    : user?.username || "User"}
                </span>
              </div>
            </div>

            {/* Search */}
            <div className="flex items-center space-x-4">
              <div className="relative">
                <Search className="absolute left-3 top-1/2 transform -translate-y-1/2 text-muted-foreground h-4 w-4" />
                <Input
                  placeholder="Search issues..."
                  className="pl-10 w-80"
                  data-testid="input-search"
                />
              </div>
            </div>
          </div>
        </header>

        <div className="p-6">
          {/* Stats Overview */}

          {user?.role === "admin" &&
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <Card>
              <CardContent className="p-6">
                <div className="flex items-center justify-between">
                  <div>
                    <p className="text-sm font-medium text-muted-foreground">Total Questions</p>
                    <p className="text-3xl font-bold text-foreground">{questions.length}</p>
                  </div>
                  <div className="w-12 h-12 bg-blue-100 dark:bg-blue-900/20 rounded-lg flex items-center justify-center">
                    <TrendingUp className="text-blue-600 dark:text-blue-400 h-6 w-6" />
                  </div>
                </div>
              </CardContent>
            </Card>

            <Card>
              <CardContent className="p-6">
                <div className="flex items-center justify-between">
                  <div>
                    <p className="text-sm font-medium text-muted-foreground">Pending Approvals</p>
                    <p className="text-3xl font-bold text-orange-600 dark:text-orange-400">{pendingApprovals}</p>
                  </div>
                  <div className="w-12 h-12 bg-orange-100 dark:bg-orange-900/20 rounded-lg flex items-center justify-center">
                    <Hourglass className="text-orange-600 dark:text-orange-400 h-6 w-6" />
                  </div>
                </div>
              </CardContent>
            </Card>

            <Card>
              <CardContent className="p-6">
                <div className="flex items-center justify-between">
                  <div>
                    <p className="text-sm font-medium text-muted-foreground">Active Users</p>
                    <p className="text-3xl font-bold text-foreground">{userCount}</p>
                  </div>
                  <div className="w-12 h-12 bg-green-100 dark:bg-green-900/20 rounded-lg flex items-center justify-center">
                    <Users className="text-green-600 dark:text-green-400 h-6 w-6" />
                  </div>
                </div>
              </CardContent>
            </Card>

            <Card>
              <CardContent className="p-6">
                <div className="flex items-center justify-between">
                  <div>
                    <p className="text-sm font-medium text-muted-foreground">Resolution Rate</p>
                    <p className="text-3xl font-bold text-foreground">100%</p>
                  </div>
                  <div className="w-12 h-12 bg-lime-100 dark:bg-lime-900/20 rounded-lg flex items-center justify-center">
                    <CheckCircle className="text-lime-600 dark:text-lime-400 h-6 w-6" />
                  </div>
                </div>
              </CardContent>
            </Card>
          </div>
           }

          {/* Filters */}
          <Card className="mb-6">
            <CardContent className="p-6">
              <div className="flex flex-wrap items-center justify-between gap-4">
                <h3 className="text-lg font-semibold text-foreground">Filter Questions</h3>

                <div className="flex flex-wrap items-center gap-3">
                  <div className="flex items-center space-x-2">
                    <label className="text-sm font-medium text-foreground">Category:</label>
                    <Select defaultValue="all">
                      <SelectTrigger className="w-40">
                        <SelectValue placeholder="All Categories" />
                      </SelectTrigger>
                      <SelectContent>
                        <SelectItem value="all">All Categories</SelectItem>
                        <SelectItem value="ibml">IBML Scanners</SelectItem>
                        <SelectItem value="softtrac">SoftTrac</SelectItem>
                        <SelectItem value="omniscan">OmniScan</SelectItem>
                      </SelectContent>
                    </Select>
                  </div>

                  <div className="flex items-center space-x-2">
                    <label className="text-sm font-medium text-foreground">Status:</label>
                    <Select defaultValue="all">
                      <SelectTrigger className="w-40">
                        <SelectValue placeholder="All Status" />
                      </SelectTrigger>
                      <SelectContent>
                        <SelectItem value="all">All Status</SelectItem>
                        <SelectItem value="pending">Pending</SelectItem>
                        <SelectItem value="approved">Approved</SelectItem>
                        <SelectItem value="rejected">Rejected</SelectItem>
                      </SelectContent>
                    </Select>
                  </div>

                  <div className="flex items-center space-x-2">
                    <label className="text-sm font-medium text-foreground">Sort by:</label>
                    <Select defaultValue="recent">
                      <SelectTrigger className="w-40">
                        <SelectValue />
                      </SelectTrigger>
                      <SelectContent>
                        <SelectItem value="trending">Trending</SelectItem>
                        <SelectItem value="recent">Most Recent</SelectItem>
                        <SelectItem value="views">Most Viewed</SelectItem>
                        <SelectItem value="answers">Most Answers</SelectItem>
                      </SelectContent>
                    </Select>
                  </div>
                </div>
              </div>
            </CardContent>
          </Card>

          {/* Questions List */}
          <div className="space-y-4">
            <div className="flex items-center justify-between">
              <h3 className="text-xl font-semibold text-foreground">Questions</h3>
              <div className="flex space-x-2">
                <Button
                  onClick={() => setShowRaiseIssueModal(true)}
                  className="bg-lime-green text-dark-green hover:bg-lime-green/90"
                  data-testid="button-raise-issue-main"
                >
                  <Plus className="mr-2 h-4 w-4" />
                  Raise Issue
                </Button>

                {user?.role === 'admin' && (
                  <Button
                    onClick={() => setShowAdminPostModal(true)}
                    className="bg-lime-green text-dark-green hover:bg-lime-green/90"
                    data-testid="button-post-issue-answer-main"
                  >
                    <Plus className="mr-2 h-4 w-4" />
                    Post Issue + Answer
                  </Button>
                )}
              </div>
            </div>

            <div className="space-y-4">
              {questions.length > 0 ? (
                questions.map((question) => (
                    // <p>{question.description}</p>
                  <QuestionCard
                    key={question.id}
                    question={{
                      id: question.id,
                      title: question.title,
                      description: question.description,
                      category: question.category,
                      status: question.status,
                      createdAt: question.created_at,
                      authorName: question.author
                        ? `${question.author.first_name ?? ''} ${question.author.last_name ?? ''}`.trim() || question.author.username
                        : 'Anonymous',
                    } as any}
                  />
                ))
              ) : (
                <Card>
                  <CardContent className="p-8 text-center text-muted-foreground">
                    No questions found. Be the first to raise an issue!
                  </CardContent>
                </Card>
              )}
            </div>
          </div>
        </div>
      </main>

      {/* Modals connected to state handlers */}
      <RaiseIssueModal
        open={showRaiseIssueModal}
        onOpenChange={setShowRaiseIssueModal}
      />

      <AdminPostModal
        open={showAdminPostModal}
        onOpenChange={setShowAdminPostModal}
      />
    </div>
  );
}
