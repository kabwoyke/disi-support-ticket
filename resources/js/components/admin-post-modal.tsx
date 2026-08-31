import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
} from "@/components/ui/dialog";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Textarea } from "@/components/ui/textarea";
import { Label } from "@/components/ui/label";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Send, Plus, Upload } from "lucide-react";

interface AdminPostModalProps {
  open: boolean;
  onOpenChange: (open: boolean) => void;
}

export function AdminPostModal({ open, onOpenChange }: AdminPostModalProps) {
  return (
    <Dialog open={open} onOpenChange={onOpenChange}>
      <DialogContent
        className="sm:max-w-3xl w-[90vw] max-h-[85vh] overflow-y-auto p-6"
        data-testid="admin-post-modal"
      >
        <DialogHeader className="mb-2">
          <DialogTitle className="flex items-center space-x-2 text-xl font-bold">
            <Plus className="h-5 w-5 text-dark-green" />
            <span>Post Issue + Answer</span>
          </DialogTitle>
        </DialogHeader>

        <form onSubmit={(e) => e.preventDefault()} className="space-y-6">
          {/* Question Section */}
          <Card className="shadow-sm">
            <CardHeader className="pb-3">
              <CardTitle className="text-lg">Question Details</CardTitle>
            </CardHeader>
            <CardContent className="space-y-4">
              <div className="space-y-2">
                <Label htmlFor="title" className="text-sm font-semibold">Issue Title *</Label>
                <Input
                  id="title"
                  data-testid="input-question-title"
                  placeholder="Brief description of the issue..."
                />
              </div>

              <div className="space-y-2">
                <Label htmlFor="description" className="text-sm font-semibold">Detailed Description *</Label>
                <Textarea
                  id="description"
                  data-testid="textarea-question-description"
                  placeholder="Provide detailed information about the issue, steps to reproduce, and any relevant context..."
                  rows={4}
                  className="resize-none"
                />
              </div>

              <div className="space-y-2">
                <Label htmlFor="category" className="text-sm font-semibold">Category *</Label>
                <Select>
                  <SelectTrigger data-testid="select-question-category">
                    <SelectValue placeholder="Select a category" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="ibml">IBML Scanners</SelectItem>
                    <SelectItem value="softtrac">SoftTrac</SelectItem>
                    <SelectItem value="omniscan">OmniScan</SelectItem>
                  </SelectContent>
                </Select>
              </div>

              {/* Question Image Attachment */}
              <div className="space-y-2">
                <Label className="text-sm font-semibold">Attach Image to Question (Optional)</Label>
                <Button
                  type="button"
                  variant="outline"
                  className="w-full h-16 border-dashed border-2 border-muted-foreground/30 hover:border-primary/50 transition-colors"
                  data-testid="button-question-upload"
                >
                  <div className="flex items-center space-x-2">
                    <Upload className="h-4 w-4 text-muted-foreground" />
                    <span className="text-sm text-muted-foreground">Upload question image</span>
                  </div>
                </Button>
              </div>
            </CardContent>
          </Card>

          {/* Answer Section */}
          <Card className="shadow-sm">
            <CardHeader className="pb-3">
              <CardTitle className="text-lg">Solution/Answer</CardTitle>
            </CardHeader>
            <CardContent className="space-y-4">
              <div className="space-y-2">
                <Label htmlFor="answer" className="text-sm font-semibold">Provide Solution *</Label>
                <Textarea
                  id="answer"
                  data-testid="textarea-answer-text"
                  placeholder="Provide the solution or answer to this issue..."
                  rows={5}
                  className="resize-none"
                />
              </div>

              {/* Answer Image Attachment */}
              <div className="space-y-2">
                <Label className="text-sm font-semibold">Attach Image to Answer (Optional)</Label>
                <Button
                  type="button"
                  variant="outline"
                  className="w-full h-16 border-dashed border-2 border-muted-foreground/30 hover:border-primary/50 transition-colors"
                  data-testid="button-answer-upload"
                >
                  <div className="flex items-center space-x-2">
                    <Upload className="h-4 w-4 text-muted-foreground" />
                    <span className="text-sm text-muted-foreground">Upload answer image</span>
                  </div>
                </Button>
              </div>
            </CardContent>
          </Card>

          <div className="flex justify-end space-x-3 pt-4 border-t border-border">
            <Button
              type="button"
              variant="outline"
              onClick={() => onOpenChange(false)}
              data-testid="button-cancel"
            >
              Cancel
            </Button>
            <Button
              type="submit"
              data-testid="button-submit"
              className="bg-lime-green text-dark-green hover:bg-lime-green/90 font-bold px-6"
            >
              <Send className="mr-2 h-4 w-4" />
              POST QUESTION + ANSWER
            </Button>
          </div>
        </form>
      </DialogContent>
    </Dialog>
  );
}
