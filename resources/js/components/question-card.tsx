import { Card, CardContent } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Clock, Eye, MessageCircle, User, Check, X, Trash2 } from "lucide-react";
import { Badge } from "@/components/ui/badge";

interface QuestionCardProps {
  question?: {
    id?: number;
    title?: string;
    description?: string;
    category?: string;
    status?: string;
    attachment?: string;
    createdAt?: string;
    views?: number;
    answerCount?: number;
    author?: {
      firstName?: string;
      lastName?: string;
      username?: string;
    };
  };
}

export function QuestionCard({ question }: QuestionCardProps = {}) {
  const category = question?.category || "ibml";
  const status = question?.status || "approved";

  return (
    <Card className="hover:shadow-md transition-shadow cursor-pointer">
      <CardContent className="p-6">
        <div className="flex items-start justify-between">
          <div className="flex-1">
            <div className="flex items-center space-x-3 mb-3">
              <Badge className="bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300">
                IBML Scanners
              </Badge>

              <Badge className="bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300">
                <Check className="mr-1 h-3 w-3" />
                Approved
              </Badge>
            </div>

            <h4 className="text-lg font-semibold text-foreground mb-2 hover:text-primary">
              {question?.title || "Sample Question Title"}
            </h4>

            <p className="text-muted-foreground mb-4 line-clamp-2">
              {question?.description || "This is a placeholder description for previewing the static card layout."}
            </p>

            {question?.attachment && (
              <div className="mb-4">
                <img
                  src={`/uploads/${question.attachment}`}
                  alt="Question attachment"
                  className="max-w-full h-auto rounded-lg border"
                  style={{ maxHeight: '200px' }}
                />
              </div>
            )}

            <div className="flex items-center justify-between">
              <div className="flex items-center space-x-4 text-sm text-muted-foreground">
                <div className="flex items-center space-x-1">
                  <User className="h-4 w-4" />
                  <span>
                    {question?.author?.firstName && question?.author?.lastName
                      ? `${question.author.firstName} ${question.author.lastName}`
                      : question?.author?.username || "John Doe"
                    }
                  </span>
                </div>
                <div className="flex items-center space-x-1">
                  <Clock className="h-4 w-4" />
                  <span>2h ago</span>
                </div>
                <div className="flex items-center space-x-1">
                  <Eye className="h-4 w-4" />
                  <span>{question?.views ?? 24} views</span>
                </div>
              </div>

              <div className="flex items-center space-x-2">
                <Badge variant="secondary" className="flex items-center">
                  <MessageCircle className="mr-1 h-3 w-3" />
                  {question?.answerCount ?? 3}
                </Badge>

                {/* Admin approval actions */}
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

                {/* Admin delete actions */}
                <div className="flex items-center space-x-2">
                  <Button
                    variant="ghost"
                    size="sm"
                    className="text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20"
                  >
                    <Trash2 className="h-4 w-4" />
                  </Button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </CardContent>
    </Card>
  );
}
