import { Sidebar } from "@/components/sidebar";
import { RaiseIssueModal } from "@/components/raise-issue-modal";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Badge } from "@/components/ui/badge";
import { Button } from "@/components/ui/button";
import { Textarea } from "@/components/ui/textarea";
import { Label } from "@/components/ui/label";
import { ArrowLeft, Clock, Eye, User, MessageCircle, Send, Check, X } from "lucide-react";
import '../../css/solves.css'
import { router, usePage } from "@inertiajs/react";
import { PageProps as InertiaPageProps, PageProps as Page } from "@inertiajs/core";
import moment from 'moment'

export interface Author {
  id: number;
  username: string;
  first_name?: string | null;
  last_name?: string | null;
}

export interface Answer {
  id?: number;
  question_id?: number;
  answer_text: string;
  status: "pending" | "approved" | "rejected";
  attachment?: string | null;
  created_at?: string;
}

export interface Question {
  id: number;
  title: string;
  description: string;
  category: string;
  priority?: string;
  status: "pending" | "approved" | "rejected";
  views: number;
  is_final: boolean;
  attachment?: string | null;
  created_by: number;
  created_at?: string;
  author?: Author;
  answer?: Answer[];
}

interface PageProps extends InertiaPageProps {
  question: Question;
  solves?: {
    user?: {
      id: number;
      username: string;
      role: string;
    };
  };
  errors?: Record<string, string>;
}
export default function QuestionDetail() {
    const q = usePage<PageProps>().props
    console.log(q)
  return (
    <div className="min-h-screen bg-background">
      <Sidebar />

      <main className="ml-64 min-h-screen">
        {/* Header */}
        <header className="bg-card border-b border-border p-6">
          <div className="flex items-center justify-between">
            <div className="flex items-center space-x-4">
              <Button variant="outline" size="sm" onClick={() => router.get("/disi-solves/dashboard")}>
                <ArrowLeft className="mr-2 h-4 w-4" />
                Back to Dashboard
              </Button>
              <h2 className="text-2xl font-bold text-foreground">Question Details</h2>
            </div>
          </div>
        </header>

        <div className="p-6 space-y-6">
          {/* Question Card */}
          <Card>
            <CardHeader>
              <div className="flex items-start justify-between">
                <div className="flex-1">
                  <div className="flex items-center space-x-3 mb-3">
                    <Badge className="bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300">
                      {q.question.category}
                    </Badge>
                    <Badge className="bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300">
                      <Check className="mr-1 h-3 w-3" />
                      {q.question.status}
                    </Badge>

                      {q.question.is_final &&
                      <Badge variant="destructive" className="bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300">FINAL ANSWER</Badge>}

                  </div>
                  <CardTitle className="text-2xl mb-4">{q.question.title}</CardTitle>
                </div>
              </div>
            </CardHeader>
            <CardContent>
              <div className="prose dark:prose-invert mb-6">
                <p className="text-foreground whitespace-pre-wrap">
                  {q.question.description}
                </p>

                <div className="mt-4">
                 {q?.question?.attachment && (
  <img
    src={
      q.question.attachment.startsWith("http")
        ? q.question.attachment
        : `/storage/${q.question.attachment}`
    }
    alt="Question attachment"
    className="max-w-full h-auto rounded-lg border max-h-[300px]"
  />
)}
                </div>
              </div>

              <div className="flex items-center justify-between pt-4 border-t border-border">
                <div className="flex items-center space-x-4 text-sm text-muted-foreground">
                  <div className="flex items-center space-x-1">
                    <User className="h-4 w-4" />
                    <span>John Doe</span>
                  </div>
                  <div className="flex items-center space-x-1">
                    <Clock className="h-4 w-4" />
                    <span>{moment(q.question.created_at).fromNow()}</span>
                  </div>
                  <div className="flex items-center space-x-1">
                    <Eye className="h-4 w-4" />
                    <span>{q.question.views} views</span>
                  </div>
                </div>

                <Badge variant="secondary" className="flex items-center">
                  <MessageCircle className="mr-1 h-3 w-3" />
                  1 answers
                </Badge>
              </div>
            </CardContent>
          </Card>

          {/* Answers Section */}
          <Card>
            <CardHeader>
              <CardTitle>Answers ({q.question.answer?.length})</CardTitle>
            </CardHeader>
            <CardContent>
              <div className="space-y-6">
                <div className="border-b border-border last:border-b-0 pb-6 last:pb-0">
                  <div className="flex items-start justify-between mb-3">
                    <div className="flex items-center space-x-3">
                      <Badge className="bg-orange-100 text-orange-800 dark:bg-orange-900/20 dark:text-orange-300">
                        <Clock className="mr-1 h-3 w-3" />
                        Pending
                      </Badge>
                    </div>

                    {/* Admin Action Preview Controls */}
                    <div className="flex items-center space-x-2">
                      <Button
                        variant="ghost"
                        size="sm"
                        className="text-green-600 hover:bg-green-50 dark:hover:bg-green-900/20"
                      >
                        <Check className="h-4 w-4" />
                      </Button>
                      <Button
                        variant="ghost"
                        size="sm"
                        className="text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20"
                      >
                        <X className="h-4 w-4" />
                      </Button>
                    </div>
                  </div>

                  {q.question.answer?.map((answer) => (
  <div key={answer.id ?? Math.random()} className="prose dark:prose-invert mb-4">
    <p className="text-foreground whitespace-pre-wrap">
      {answer.answer_text}
    </p>

    {/* Conditionally render the image only when an attachment exists */}
    {answer.attachment && (
      <div className="mt-4">
        <img
          src={
            answer.attachment.startsWith("http")
              ? answer.attachment
              : `/storage/${answer.attachment}`
          }
          alt="Answer attachment"
          className="max-w-full h-auto rounded-lg border max-h-[300px]"
        />
      </div>
    )}
  </div>
))}


                  <div className="flex items-center space-x-4 text-sm text-muted-foreground">
                    <div className="flex items-center space-x-1">
                      <User className="h-4 w-4" />
                      <span>Jane Smith</span>
                    </div>
                    <div className="flex items-center space-x-1">
                      <Clock className="h-4 w-4" />
                      <span>1h ago</span>
                    </div>
                  </div>
                </div>
              </div>
            </CardContent>
          </Card>

          {/* Answer Form UI */}
          <Card>
            <CardHeader>
              <CardTitle>Submit an Answer</CardTitle>
            </CardHeader>
            <CardContent>
              <form onSubmit={(e) => e.preventDefault()} className="space-y-4">
                <div>
                  <Label htmlFor="answer">Your Answer</Label>
                  <Textarea
                    id="answer"
                    rows={6}
                    placeholder="Provide a detailed answer to help solve this issue..."
                  />
                </div>

                <div>
                  <Label htmlFor="answer-image">Attach Image (Optional)</Label>
                  <div className="mt-2">
                    <input
                      type="file"
                      id="answer-image"
                      accept="image/jpeg,image/png,image/gif,image/webp"
                      className="block w-full text-sm text-gray-500
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-md file:border-0
                        file:text-sm file:font-semibold
                        file:bg-blue-50 file:text-blue-700
                        hover:file:bg-blue-100"
                    />
                  </div>

                  {/* <div className="mt-4">
                    <img
                      src="/uploads/preview-placeholder.png"
                      alt="Preview"
                      className="max-w-full h-auto rounded-lg border max-h-[200px]"
                    />
                    <Button
                      type="button"
                      variant="ghost"
                      size="sm"
                      className="mt-2 text-red-600"
                    >
                      Remove Image
                    </Button>
                  </div> */}
                </div>

                <div className="flex justify-end">
                  <Button
                    type="submit"
                    className="bg-lime-green text-dark-green hover:bg-lime-green/90"
                  >
                    <Send className="mr-2 h-4 w-4" />
                    Submit Answer
                  </Button>
                </div>
              </form>
            </CardContent>
          </Card>
        </div>
      </main>

      <RaiseIssueModal
        open={false}
        onOpenChange={() => {}}
      />
    </div>
  );
}
